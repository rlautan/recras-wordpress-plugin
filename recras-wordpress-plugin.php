<?php
/**
 * @package Recras WordPress Plugin
 * @version 0.0.1
 */
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: http://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Version: 0.0.1
Author URI: http://www.recras.nl/
*/

define('TEXT_DOMAIN', 'recras-wp');
define('WPPC_BASE_URL', plugin_dir_url(__FILE__));


class RecrasPlugin
{
    public function __construct()
    {
        /** Init Localisation */
        load_default_textdomain();
        load_plugin_textdomain(TEXT_DOMAIN, PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/lang');

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
