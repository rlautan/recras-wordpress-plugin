<?php
namespace Recras;

require_once('recrasSettings.php');

// Debugging
error_reporting(-1);
ini_set('display_errors', 'On');

/**
 * @package Recras WordPress Plugin
 * @version 0.4.0
 */
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: http://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Version: 0.4.0
Author URI: http://www.recras.nl/
*/

class Plugin
{
    const TEXT_DOMAIN = 'recras-wp';
    const VALID_OPTIONS = ['title', 'persons', 'price_pp_excl_vat', 'price_pp_incl_vat', 'price_total_excl_vat', 'price_total_incl_vat', 'program', 'programme'];

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
        if (!isset($attributes['show'])) {
            return __('Error: "show" option not set', $this::TEXT_DOMAIN);
        }
        if (!in_array($attributes['show'], $this::VALID_OPTIONS)) {
            return __('Error: invalid "show" option', $this::TEXT_DOMAIN);
        }

        $subdomain = get_option('recras_subdomain');
        if (!$subdomain) {
            return __('Error: you have not set your Recras subdomain yet', $this::TEXT_DOMAIN);
        }
        $currency = get_option('recras_currency');

        $arrangementID = $attributes['id'];
        $json = @file_get_contents('https://' . $subdomain . '.recras.nl/api2.php/arrangementen/' . $arrangementID);
        if ($json === false) {
            return __('Error: could not retrieve external data', $this::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', $this::TEXT_DOMAIN);
        }

        switch ($attributes['show']) {
            case 'title':
                return '<span class="recras_title">' . $json->arrangement . '</span>';
            case 'persons':
                return '<span class="recras_persons">' . $json->aantal_personen . '</span>';
            case 'price_pp_excl_vat':
                return '<span class="recras_price">' . $currency . ' ' . $json->prijs_pp_exc . '</span>';
            case 'price_pp_incl_vat':
                return '<span class="recras_price">' . $currency . ' ' . $json->prijs_pp_inc . '</span>';
            case 'price_total_excl_vat':
                return '<span class="recras_price">' . $currency . ' ' . $json->prijs_totaal_exc . '</span>';
            case 'price_total_incl_vat':
                return '<span class="recras_price">' . $currency . ' ' . $json->prijs_totaal_inc . '</span>';
            case 'program':
            case 'programme':
                $startTime = (isset($attributes['starttime']) ? $attributes['starttime'] : '00:00');
                return $this->generateProgramme($json->programma, $startTime);
            default:
                return 'Error: unknown option';
        }
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

    public function generateProgramme($programme, $startTime = '00:00')
    {
        $html  = '';
        $html .= '<table class="recras-programme">';
        $html .= '<thead>';
        $html .= '<tr><th>' . __('From', $this::TEXT_DOMAIN) . '<th>' . __('Until', $this::TEXT_DOMAIN) . '<th>' . __('Activity', $this::TEXT_DOMAIN);
        $html .= '</thead>';
        $html .= '<tbody>';
        $lastTime = null;
        foreach ($programme as $activity) {
            if (!$activity->omschrijving) {
                continue;
            }
            $startDate = new \DateTime($startTime);
            $endDate = new \DateTime($startTime);
            $timeBegin = new \DateInterval($activity->begin);
            $timeEnd = new \DateInterval($activity->eind);
            $startFormatted = $startDate->add($timeBegin)->format('H:i');
            $class = (!is_null($lastTime) && $startFormatted < $lastTime) ? ' class="new-day"' : '';

            $html .= '<tr' . $class . '><td>' . $startFormatted;
            $html .= '<td>' . $endDate->add($timeEnd)->format('H:i');
            $html .= '<td>' . $activity->omschrijving;
            $lastTime = $startFormatted;
        }
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
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
