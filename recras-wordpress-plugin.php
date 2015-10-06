<?php
namespace Recras;

require_once('recrasSettings.php');

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

        add_action('admin_init', ['Recras\Settings', 'registerSettings']);

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

        $arrangementID = $attributes['id'];
        $json = @file_get_contents('https://demo.recras.nl/api2.php/arrangementen/' . $arrangementID);
        if ($json === false) {
            die(__('Error: could not retrieve external data', $this::TEXT_DOMAIN));
        }
        $json = json_decode($json);
        if (is_null($json)) {
            die(__('Error: could not parse external data', $this::TEXT_DOMAIN));
        }

        return 'ARRANGEMENT';
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
    }

    public function addShortcodes()
    {
        add_shortcode('arrangement', [$this, 'addArrangementShortcode']);
    }

    public function sanitizeSubdomain($subdomain)
    {
        // RFC 1034 section 3.5 - http://tools.ietf.org/html/rfc1034#section-3.5
        if (strlen($subdomain) > 63) {
            return false;
        }
        if (! preg_match('/^[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?$/', $subdomain)) {
            return false;
        }
        return $subdomain;
    }
}
$recras = new Plugin;
