<?php
namespace Recras;

class Plugin
{
    const TEXT_DOMAIN = 'recras-wp';


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

        add_action('admin_notices', [&$this, 'showActivationNotice']);

        add_action('init', [&$this, 'addEditorButtons']);

        add_filter('mce_external_languages', [&$this, 'loadEditorLanguage']);

        add_action('admin_enqueue_scripts', [$this, 'loadAdminScripts']);
        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);

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
        $plugins['recras'] = plugins_url('/editor/plugin.js', dirname(__FILE__));
        return $plugins;
    }


    /**
     * Add the menu items for our plugin
     */
    public function addMenuItems()
    {
        add_options_page(
            'Recras',
            'Recras',
            'manage_options',
            'recras',
            ['\Recras\Settings', 'editSettings']
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


    public function loadAdminScripts()
    {
        wp_register_script('recras-admin', plugins_url('/js/admin.js', dirname(__FILE__)), [], '1.0.0', true);
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
        wp_register_script('recras', plugins_url('/js/recras.js', dirname(__FILE__)), ['jquery'], '1.2.1', true);
        wp_localize_script('recras', 'recras_l10n', [
            'loading' => __('Loading...', $this::TEXT_DOMAIN),
            'sent_success' => __('Your message was sent successfully', $this::TEXT_DOMAIN),
            'sent_error' => __('There was an error sending your message', $this::TEXT_DOMAIN),
        ]);
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
     * Show a notice if the server doesn't meet our requirements
     */
    public function showActivationNotice()
    {
        global $pagenow;

        if ($pagenow === 'plugins.php' && !extension_loaded('curl')) {
            echo '<div class="update-nag notice is-dismissible">' . __('The cURL extension for PHP is not installed. Without this, submitting contact forms will not work.', $this::TEXT_DOMAIN) . '</div>';
        }
    }
}
