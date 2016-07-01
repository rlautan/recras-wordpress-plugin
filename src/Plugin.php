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

        add_action('init', [&$this, 'addEditorButtons']);
        add_action('init', [&$this, 'setBaseUrl']);

        add_filter('mce_external_languages', [&$this, 'loadEditorLanguage']);

        add_action('admin_enqueue_scripts', [$this, 'loadAdminScripts']);
        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);

        // Clear caches
        add_action('admin_post_clear_arrangement_cache', ['Recras\Arrangement', 'clearCache']);
        add_action('admin_post_clear_contactform_cache', ['Recras\ContactForm', 'clearCache']);
        add_action('admin_post_clear_product_cache', ['Recras\Products', 'clearCache']);

        $this->addShortcodes();
    }


    /**
     * Add the shortcode generator buttons to TinyMCE
     */
    public function addEditorButtons()
    {
        add_filter('mce_external_plugins', [&$this, 'addEditorScripts']);
        add_filter('mce_buttons', [&$this, 'registerEditorButtons']);
        add_thickbox();
    }


    /**
     * Load the script needed for TinyMCE
     *
     * @param array $plugins
     *
     * @return array
     */
    public function addEditorScripts($plugins)
    {
        $plugins['recras'] = $this->baseUrl . '/editor/plugin.js';
        return $plugins;
    }


    /**
     * Add the menu items for our plugin
     */
    public function addMenuItems()
    {
        add_menu_page('Recras', 'Recras', 'edit_pages', 'recras', '', plugin_dir_url(dirname(__FILE__)) . 'logo.svg', 58);

        add_submenu_page(
            'recras',
            __('Settings', $this::TEXT_DOMAIN),
            __('Settings', $this::TEXT_DOMAIN),
            'manage_options',
            'recras',
            ['\Recras\Settings', 'editSettings']
        );
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
     * @param int $errors
     *
     * @return string
     */
    public static function getStatusMessage($errors)
    {
        return ($errors === 0 ? 'success' : 'error');
    }


    public function loadAdminScripts()
    {
        wp_register_script('recras-admin', $this->baseUrl .'/js/admin.js', [], '1.0.0', true);
        wp_localize_script('recras-admin', 'recras_l10n', [
            'no_connection' => __('Could not connect to your Recras', $this::TEXT_DOMAIN),
        ]);
        wp_enqueue_script('recras-admin');
    }


    /**
     * Load the TinyMCE translation
     *
     * @param array $locales
     *
     * @return array
     */
    public function loadEditorLanguage($locales)
    {
        $locales['recras'] = plugin_dir_path(__FILE__) . '/editor/translation.php';
        return $locales;
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
     * Register TinyMCE buttons
     *
     * @param array $buttons
     *
     * @return array
     */
    public function registerEditorButtons($buttons)
    {
        array_push($buttons, 'recras-arrangement', 'recras-booking', 'recras-contact', 'recras-product');
        return $buttons;
    }


    /**
     * Set plugin base dir
     */
    public function setBaseUrl()
    {
        $this->baseUrl = rtrim(plugins_url('', dirname(__FILE__)), '/');
    }
}
