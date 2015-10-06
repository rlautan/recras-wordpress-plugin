<?php
namespace Recras;

// Debugging
error_reporting(-1);
ini_set('display_errors', 'On');

/**
 * @package Recras WordPress Plugin
 * @version 0.2.0
 */
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: http://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Version: 0.2.0
Author URI: http://www.recras.nl/
*/

class Plugin
{
    const TEXT_DOMAIN = 'recras-wp';

    public function __construct()
    {
        // Init Localisation
        load_default_textdomain();
        load_plugin_textdomain($this::TEXT_DOMAIN, false, PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/lang');

        // Add admin menu pages
        add_action('admin_menu', [&$this, 'addMenuItems']);

        $this->addShortcodes();
    }

    public function addArrangementShortcode($attributes)
    {
        if (!isset($attributes['id'])) {
            return __('Error: no ID set', $this::TEXT_DOMAIN);
        }
        if (!ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', $this::TEXT_DOMAIN);
        }

        return 'ARRANGEMENT';
    }

    public function addMenuItems()
    {
        //TODO: not sure about  manage_options  capability
        add_menu_page(__('Recras settings', $this::TEXT_DOMAIN), __('Recras settings', $this::TEXT_DOMAIN), 'manage_options', 'recras-settings', [&$this, 'editSettings'], 'dashicons-admin-generic', '101.1');
    }

    public function addShortcodes()
    {
        add_shortcode('arrangement', [$this, 'addArrangementShortcode']);
    }

    public function editSettings()
    {
        die('TODO');
    }
}
$recras = new Plugin;
