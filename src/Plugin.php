<?php
namespace Recras;

class Plugin
{
    const LIBRARY_VERSION = '0.13.4';
    const TEXT_DOMAIN = 'recras-wp';

    const SHORTCODE_ONLINE_BOOKING = 'recras-booking';
    const SHORTCODE_VOUCHERS = 'recras-vouchers';

    public $baseUrl;


    /**
     * Init all the things!
     */
    public function __construct()
    {
        $this->setBaseUrl();

        // Init Localisation
        load_default_textdomain();
        load_plugin_textdomain($this::TEXT_DOMAIN, false, dirname(plugin_basename(dirname(__FILE__))) . '/lang');

        // Add admin menu pages
        add_action('admin_menu', [&$this, 'addMenuItems']);

        add_action('admin_init', ['Recras\Settings', 'registerSettings']);
        add_action('admin_init', ['Recras\Editor', 'addButtons']);

        add_action('init', [&$this, 'addGutenbergButtons']);
        add_filter('block_categories', [$this, 'addGutenbergRecrasCategory']);

        add_action('admin_enqueue_scripts', [$this, 'loadAdminScripts']);
        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);

        // Clear caches
        add_action('admin_post_clear_arrangement_cache', ['Recras\Arrangement', 'clearCache']);
        add_action('admin_post_clear_contactform_cache', ['Recras\ContactForm', 'clearCache']);
        add_action('admin_post_clear_product_cache', ['Recras\Products', 'clearCache']);

        $this->addShortcodes();

        if (version_compare(get_bloginfo('version'), '5.0', '>=')) {
            add_action('tgmpa_register', [$this, 'registerRequiredPlugins']);
        }
    }


    public static function addGutenbergButtons()
    {
        $gutenbergName = 'gutenberg-recras-buttons';
        wp_register_script($gutenbergName, plugins_url('js/gutenberg.js', __DIR__), ['wp-blocks', 'wp-element']);

        wp_register_style($gutenbergName, plugins_url('css/gutenberg.css', __DIR__), ['wp-edit-blocks'], filemtime(plugin_dir_path(__FILE__) . '../css/gutenberg.css'));

        $gutenbergBlocks = [
            'availability' => ['Recras\Availability', 'addAvailabilityShortcode'],
            'contactform' => ['Recras\ContactForm', 'addContactShortcode'],
            'onlinebooking' => ['Recras\OnlineBooking', 'addBookingShortcode'],
            'package' => ['Recras\Arrangement', 'addArrangementShortcode'],
            'product' => ['Recras\Products', 'addProductShortcode'],
            'voucher' => ['Recras\Vouchers', 'addVoucherShortcode'],
        ];
        foreach ($gutenbergBlocks as $key => $callback) {
            register_block_type('recras/' . $key, [
                'editor_script' => $gutenbergName,
                'editor_style' => $gutenbergName,
                'render_callback' => $callback,
            ]);
        }
    }

    public static function addGutenbergRecrasCategory($categories)
    {
        $categories[] = [
            'slug' => 'recras',
            'title' => 'Recras',
        ];
        return $categories;
    }


    /**
     * Add the menu items for our plugin
     */
    public function addMenuItems()
    {
        $mainPage = current_user_can('manage_options') ? 'recras' : 'recras-clear-cache';
        add_menu_page('Recras', 'Recras', 'edit_pages', $mainPage, '', plugin_dir_url(dirname(__FILE__)) . 'logo.svg', 58);

        if (current_user_can('manage_options')) {
            add_submenu_page(
                'recras',
                __('Settings', $this::TEXT_DOMAIN),
                __('Settings', $this::TEXT_DOMAIN),
                'manage_options',
                'recras',
                ['\Recras\Settings', 'editSettings']
            );
        }

        add_submenu_page(
            'recras',
            __('Cache', $this::TEXT_DOMAIN),
            __('Cache', $this::TEXT_DOMAIN),
            'edit_pages',
            'recras-clear-cache',
            ['\Recras\Settings', 'clearCache']
        );

        add_submenu_page(null, __('Package', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-arrangement', ['Recras\Arrangement', 'showForm']);
        add_submenu_page(null, __('Package availability', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-package-availability', ['Recras\Availability', 'showForm']);
        add_submenu_page(null, __('Contact form', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-contact', ['Recras\ContactForm', 'showForm']);
        add_submenu_page(null, __('Online booking', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-booking', ['Recras\OnlineBooking', 'showForm']);
        add_submenu_page(null, __('Product', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-product', ['Recras\Products', 'showForm']);
        add_submenu_page(null, __('Vouchers', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-vouchers', ['Recras\Vouchers', 'showForm']);
    }


    /**
     * Register our shortcodes
     */
    public function addShortcodes()
    {
        add_shortcode('recras-availability', ['Recras\Availability', 'addAvailabilityShortcode']);
        add_shortcode($this::SHORTCODE_ONLINE_BOOKING, ['Recras\OnlineBooking', 'addBookingShortcode']);
        add_shortcode('recras-contact', ['Recras\ContactForm', 'addContactShortcode']);
        add_shortcode('recras-package', ['Recras\Arrangement', 'addArrangementShortcode']);
        add_shortcode('recras-product', ['Recras\Products', 'addProductShortcode']);
        add_shortcode($this::SHORTCODE_VOUCHERS, ['Recras\Vouchers', 'addVoucherShortcode']);
    }


    /**
     * Get error message if no subdomain has been entered yet
     * @return string
     */
    public static function getNoSubdomainError()
    {
        if (current_user_can('manage_options')) {
            return __('Error: you have not set your Recras name yet', Plugin::TEXT_DOMAIN);
        } else {
            return __('Error: your Recras name has not been set yet, but you do not have the permission to set this. Please ask your site administrator to do this for you.', Plugin::TEXT_DOMAIN);
        }
    }


    /**
     * @param int $errors
     *
     * @return string
     */
    public static function getStatusMessage($errors)
    {
        return ($errors === 0 ? 'success' : 'error');
    }


    /**
     * Load scripts for use in the WP admin
     */
    public function loadAdminScripts()
    {
        wp_register_script('recras-admin', $this->baseUrl . '/js/admin.js', [], '1.10.1', true);
        wp_localize_script('recras-admin', 'recras_l10n', [
            'contact_form' => __('Contact form', $this::TEXT_DOMAIN),
            'no_connection' => __('Could not connect to your Recras', $this::TEXT_DOMAIN),
            'online_booking' => __('Online booking', $this::TEXT_DOMAIN),
            'package' => __('Package', $this::TEXT_DOMAIN),
            'package_availability' => __('Package availability', $this::TEXT_DOMAIN),
            'product' => __('Product', $this::TEXT_DOMAIN),
            'vouchers' => __('Vouchers', $this::TEXT_DOMAIN),
        ]);
        wp_enqueue_script('recras-admin');
        wp_enqueue_style('recras-admin-style', $this->baseUrl . '/css/admin-style.css', [], '1.10.1');
    }


    /**
     * Load the general script and localisation
     */
    public function loadScripts()
    {
        $localisation = [
            'loading' => __('Loading...', $this::TEXT_DOMAIN),
            'sent_success' => __('Your message was sent successfully', $this::TEXT_DOMAIN),
            'sent_error' => __('There was an error sending your message', $this::TEXT_DOMAIN),
        ];

        if ($value = get_option('recras_datetimepicker')) {
            wp_enqueue_script('momentjs', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/moment.min.js', [], false, true); // ver=false because it's already in the URL
            wp_enqueue_script('momentjs-nl', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.1/locale/nl.js', ['momentjs'], false, true);
            wp_enqueue_script('datetimepicker', $this->baseUrl . '/datetimepicker/bootstrap-material-datetimepicker.js', ['momentjs'], '20170208', true);
            wp_enqueue_style('datetimepicker', $this->baseUrl . '/datetimepicker/bootstrap-material-datetimepicker.css', [], '20170208');
            wp_enqueue_style('material-font', 'https://fonts.googleapis.com/icon?family=Material+Icons');

            $localisation['language'] = get_locale();
            $localisation['button_cancel'] = __('Cancel', $this::TEXT_DOMAIN);
            $localisation['button_ok'] = __('OK', $this::TEXT_DOMAIN);
        }

        global $post;
        if (
            strpos($post->post_content, $this::SHORTCODE_ONLINE_BOOKING) !== false ||
            strpos($post->post_content, $this::SHORTCODE_VOUCHERS)
        ) {
            wp_enqueue_script('polyfill', 'https://cdn.polyfill.io/v2/polyfill.min.js?features=default,fetch,Promise', [], null, false);
            wp_enqueue_script('recrasjslibrary', $this->baseUrl . '/js/onlinebooking.min.js', [], $this::LIBRARY_VERSION, false);

            $theme = get_option('recras_theme');
            if (!$theme) {
                $theme = 'none';
            }
            $allowedThemes = Settings::getThemes();
            if ($theme !== 'none' && array_key_exists($theme, $allowedThemes)) {
                wp_enqueue_style('theme_' . $theme, $this->baseUrl . '/css/themes/' . $theme . '.css', $allowedThemes[$theme]['version']);
            }
        }

        wp_register_script('recras', $this->baseUrl . '/js/recras.js', ['jquery'], '1.13.0', true);
        wp_localize_script('recras', 'recras_l10n', $localisation);
        wp_enqueue_script('recras');
    }


    public static function registerRequiredPlugins()
    {
        $plugins = [
            [
                'name' => 'Classic Editor',
                'slug' => 'classic-editor',
                'required' => false,
            ],
        ];

        $config = [
            'id' => 'recras-wp',
            'default_path' => '',
            'menu' => 'tgmpa-install-plugins',
            'parent_slug' => 'plugins.php',
            'capability' => 'manage_options',
            'has_notices' => true,
            'dismissable' => true,
            'dismiss_msg' => '',
            'is_automatic' => true, // Automatically activate plugins after installation
            'message' => '',
        ];
        tgmpa($plugins, $config);
    }

    /**
     * Set plugin base dir
     */
    public function setBaseUrl()
    {
        $this->baseUrl = rtrim(plugins_url('', dirname(__FILE__)), '/');
    }    
}
