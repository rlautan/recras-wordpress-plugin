<?php
// Debugging
error_reporting(-1);
ini_set('display_errors', 'On');

/**
 * @package Recras WordPress Plugin
 * @version 0.0.2
 */
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: http://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Version: 0.0.2
Author URI: http://www.recras.nl/
*/

class RecrasPlugin
{
    const TEXT_DOMAIN = 'recras-wp';

    public function __construct()
    {
        /** Init Localisation */
        load_default_textdomain();
        load_plugin_textdomain($this::TEXT_DOMAIN, false, PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/lang');

        $this->addShortcodes();
    }

    public function addArrangementShortcode()
    {
        return 'ARRANGEMENT';
    }

    public function addShortcodes()
    {
        add_shortcode('arrangement', [$this, 'addArrangementShortcode']);
    }
}
$recras = new RecrasPlugin;
