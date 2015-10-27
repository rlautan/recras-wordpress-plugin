<?php
namespace Recras;

class Plugin
{
    const TEXT_DOMAIN = 'recras-wp';


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

        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);

        $this->addShortcodes();
    }

    public function addEditorButtons()
    {
        add_filter('mce_external_plugins', [&$this, 'addEditorScripts']);
        add_filter('mce_buttons', [&$this, 'registerEditorButtons']);
        add_thickbox();
    }

    public function addEditorScripts($plugins)
    {
        $plugins['recras'] = plugins_url('/editor/plugin.js', dirname(__FILE__));
        return $plugins;
    }

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
    }

    public function addShortcodes()
    {
        add_shortcode('arrangement', ['Recras\Arrangement', 'addArrangementShortcode']);
        add_shortcode('recras-contact', ['Recras\ContactForm', 'addContactShortcode']);
    }

    public function loadEditorLanguage($locales)
    {
        $locales['recras'] = plugin_dir_path(__FILE__) . '/editor/translation.php';
        return $locales;
    }

    public function loadScripts()
    {
        wp_register_script('recras', plugins_url('/js/recras.js', dirname(__FILE__)), ['jquery'], '0.7.0', true);
        wp_localize_script('recras', 'recras_l10n', [
            'loading' => __('Loading...', $this::TEXT_DOMAIN),
            'sent_success' => __('Your message was sent successfully', $this::TEXT_DOMAIN),
            'sent_error' => __('There was an error sending your message', $this::TEXT_DOMAIN),
        ]);
        wp_enqueue_script('recras');
    }

    public function registerEditorButtons($buttons)
    {
        array_push($buttons, 'arrangement', 'recras-contact');
        return $buttons;
    }

    public function showActivationNotice()
    {
        global $pagenow;

        if ($pagenow === 'plugins.php' && !extension_loaded('curl')) {
            echo '<div class="update-nag notice is-dismissible">' . __('The cURL extension for PHP is not installed. Without this, submitting contact forms will not work.', $this::TEXT_DOMAIN) . '</div>';
        }
    }
}