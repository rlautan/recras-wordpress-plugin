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
        $plugin = new Plugin();

        $plugins['recras'] = $plugin->baseUrl . '/editor/plugin.js';
        return $plugins;
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
        array_push($buttons, 'recras-arrangement', 'recras-availability', 'recras-booking', 'recras-contact', 'recras-product');
        return $buttons;
    }
}
