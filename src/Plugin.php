<?php
namespace Recras;

class Plugin
{
    const TEXT_DOMAIN = 'recras-wp';

    public $baseUrl;


    /**
     * Init all the things!
     */
    public function __construct()
    {
        // Init Localisation
        load_default_textdomain();
        load_plugin_textdomain($this::TEXT_DOMAIN, false, dirname(plugin_basename(dirname(__FILE__))) . '/lang');

        // Add admin menu pages
        add_action('admin_menu', [&$this, 'addMenuItems']);

        add_action('admin_init', ['Recras\Settings', 'registerSettings']);
        add_action('admin_init', ['Recras\Editor', 'addButtons']);
        add_action('init', [&$this, 'setBaseUrl']);

        add_filter('mce_external_languages', ['Recras\Editor', 'loadTranslations']);

        add_action('admin_enqueue_scripts', [$this, 'loadAdminScripts']);
        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);

        // Clear caches
        add_action('admin_post_clear_arrangement_cache', ['Recras\Arrangement', 'clearCache']);
        add_action('admin_post_clear_contactform_cache', ['Recras\ContactForm', 'clearCache']);
        add_action('admin_post_clear_product_cache', ['Recras\Products', 'clearCache']);

        $this->addShortcodes();
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

        add_submenu_page(null, __('Arrangement', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-arrangement', ['Recras\Arrangement', 'showForm']);
        add_submenu_page(null, __('Contact form', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-contact', ['Recras\ContactForm', 'showForm']);
        add_submenu_page(null, __('Online booking', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-booking', ['Recras\OnlineBooking', 'showForm']);
        add_submenu_page(null, __('Product', $this::TEXT_DOMAIN), null, 'publish_posts', 'form-product', ['Recras\Products', 'showForm']);
    }


    /**
     * Register our shortcodes
     */
    public function addShortcodes()
    {
        add_shortcode('arrangement', ['Recras\Arrangement', 'addArrangementShortcodeOld']); // DEPRECATED
        add_shortcode('recras-arrangement', ['Recras\Arrangement', 'addArrangementShortcode']);
        add_shortcode('recras-booking', ['Recras\OnlineBooking', 'addBookingShortcode']);
        add_shortcode('recras-contact', ['Recras\ContactForm', 'addContactShortcode']);
        add_shortcode('recras-product', ['Recras\Products', 'addProductShortcode']);
    }


    /**
     * Delete a transient. Returns 0 for success, 1 for error for easy error counting
     *
     * @param $name
     *
     * @return int
     */
    public static function deleteTransient($name)
    {
        return (delete_transient($name) ? 0 : 1);
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
        wp_register_script('recras-admin', $this->baseUrl .'/js/admin.js', [], '1.10.1', true);
        wp_localize_script('recras-admin', 'recras_l10n', [
            'no_connection' => __('Could not connect to your Recras', $this::TEXT_DOMAIN),
        ]);
        wp_enqueue_script('recras-admin');
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
            wp_enqueue_script('datetimepicker', $this->baseUrl . '/datetimepicker/bootstrap-material-datetimepicker.js', ['momentjs'], '20151019', true);
            wp_enqueue_style('datetimepicker', $this->baseUrl . '/datetimepicker/bootstrap-material-datetimepicker.css', [], '20151019');
            wp_enqueue_style('material-font', 'https://fonts.googleapis.com/icon?family=Material+Icons');

            $localisation['language'] = get_locale();
            $localisation['button_cancel'] = __('Cancel', $this::TEXT_DOMAIN);
            $localisation['button_ok'] = __('OK', $this::TEXT_DOMAIN);
        }

        wp_register_script('recras', $this->baseUrl . '/js/recras.js', ['jquery'], '1.5.0', true);
        wp_localize_script('recras', 'recras_l10n', $localisation);
        wp_enqueue_script('recras');

    }


    /**
     * Set plugin base dir
     */
    public function setBaseUrl()
    {
        $this->baseUrl = rtrim(plugins_url('', dirname(__FILE__)), '/');
    }    
}
