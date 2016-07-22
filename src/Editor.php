<?php
namespace Recras;

class Editor
{
    /**
     * Add the shortcode generator buttons to TinyMCE
     */
    public static function addButtons()
    {
        add_filter('mce_buttons', ['Recras\Editor', 'registerButtons'], 999, 2);
        add_filter('mce_external_plugins', ['Recras\Editor', 'addScripts'], 999);
        add_thickbox();
    }


    /**
     * Load the script needed for TinyMCE
     *
     * @param array $plugins
     *
     * @return array
     */
    public static function addScripts($plugins)
    {
        global $recras;

        $plugins['recras'] = $recras->baseUrl . '/editor/plugin.js';
        return $plugins;
    }


    /**
     * Load the TinyMCE translation
     *
     * @param array $locales
     *
     * @return array
     */
    public static function loadTranslations($locales)
    {
        $locales['recras'] = plugin_dir_path(__FILE__) . '/editor/translation.php';
        return $locales;
    }


    /**
     * Register TinyMCE buttons
     *
     * @param array $buttons
     * @param int $editorId
     *
     * @return array
     */
    public static function registerButtons($buttons, $editorId)
    {
        array_push($buttons, 'recras-arrangement', 'recras-booking', 'recras-contact', 'recras-product');
        return $buttons;
    }
}
