<?php
namespace Recras;

require_once('recrasSettings.php');

// Debugging
if (WP_DEBUG) {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

/**
 * @package Recras WordPress Plugin
 * @version 0.6.1
 */
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: http://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Version: 0.6.1
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

        add_action('admin_notices', [&$this, 'showActivationNotice']);

        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);

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

        $json = @file_get_contents('https://' . $subdomain . '.recras.nl/api2.php/arrangementen/' . $attributes['id']);
        if ($json === false) {
            return __('Error: could not retrieve external data', $this::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', $this::TEXT_DOMAIN);
        }

        switch ($attributes['show']) {
            case 'title':
                return '<span class="recras-title">' . $json->arrangement . '</span>';
            case 'persons':
                return '<span class="recras-persons">' . $json->aantal_personen . '</span>';
            case 'price_pp_excl_vat':
                return $this->returnPrice($json->prijs_pp_exc);
            case 'price_pp_incl_vat':
                return $this->returnPrice($json->prijs_pp_inc);
            case 'price_total_excl_vat':
                return $this->returnPrice($json->prijs_totaal_exc);
            case 'price_total_incl_vat':
                return $this->returnPrice($json->prijs_totaal_inc);
            case 'program':
            case 'programme':
                $startTime = (isset($attributes['starttime']) ? $attributes['starttime'] : '00:00');
                $showHeader = true;
                if (isset($attributes['showheader']) && ($attributes['showheader'] == 'false' || $attributes['showheader'] == 0 || $attributes['showheader'] == 'no')) {
                    $showHeader = false;
                }
                return $this->generateProgramme($json->programma, $startTime, $showHeader);
            default:
                return 'Error: unknown option';
        }
    }

    public function addContactShortcode($attributes)
    {
        if (!isset($attributes['id'])) {
            return __('Error: no ID set', $this::TEXT_DOMAIN);
        }
        if (!ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', $this::TEXT_DOMAIN);
        }

        $subdomain = get_option('recras_subdomain');
        if (!$subdomain) {
            return __('Error: you have not set your Recras subdomain yet', $this::TEXT_DOMAIN);
        }


        $baseUrl = 'https://' . $subdomain . '.recras.nl/api2.php/contactformulieren/' . $attributes['id'];
        $json = @file_get_contents($baseUrl);
        if ($json === false) {
            return __('Error: could not retrieve external data', $this::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', $this::TEXT_DOMAIN);
        }
        $formTitle = $json->naam;

        $json = @file_get_contents($baseUrl . '/velden');
        if ($json === false) {
            return __('Error: could not retrieve external data', $this::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', $this::TEXT_DOMAIN);
        }
        $formFields = $json;


        if (isset($attributes['showtitle']) && ($attributes['showtitle'] == 'false' || $attributes['showtitle'] == 0 || $attributes['showtitle'] == 'no')) {
            $formTitle = false;
        }

        return $this->generateForm($attributes['id'], $formTitle, $formFields, $subdomain);
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
        add_shortcode('recras-contact', [$this, 'addContactShortcode']);
    }

    public function generateForm($formID, $formTitle, $formFields, $subdomain)
    {
        $html  = '';
        if ($formTitle) {
            $html .= '<h2>' . $formTitle . '</h2>';
        }

        $html .= '<form class="recras-contact" id="recras-form' . $formID . '">';
        $html .= '<dl>';
        foreach ($formFields as $field) {
            if ($field->soort_invoer !== 'header') {
                $html .= '<dt><label for="field' . $field->id . '">' . $field->naam . '</label>';
                if ($field->verplicht) {
                    $html .= '<span class="recras-required">*</span>';
                }
            }
            switch ($field->soort_invoer) {
                case 'boeking.datum':
                    $html .= '<dd><input id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . ' type="date" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" placeholder="yyyy-mm-dd">';
                    break;
                case 'boeking.groepsgrootte':
                    $html .= '<dd><input id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . ' type="number" min="1">';
                    break;
                case 'boeking.starttijd':
                    $html .= '<dd><input id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . ' type="time" pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])" placeholder="hh:mm">';
                    break;
                case 'contactpersoon.geslacht':
                    $html .= '<dd><select id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . '>';
                    $html .= '<option value="man">' . __('Male', $this::TEXT_DOMAIN);
                    $html .= '<option value="vrouw">' . __('Female', $this::TEXT_DOMAIN);;
                    $html .= '<option value="onbekend">' . __('Unknown', $this::TEXT_DOMAIN);;
                    $html .= '</select>';
                    break;
                case 'header':
                    if (strpos($html, '<dt') !== false) {
                        $html .= '</dl>';
                    }
                    $html .= '<h3>' . $field->naam . '</h3>';
                    if (strpos($html, '<dt') !== false) {
                        $html .= '<dl>';
                    }
                    break;
                case 'keuze':
                    $html .= '<dd><select id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . '>';
                    foreach ($field->mogelijke_keuzes as $keuze) {
                        $html .= '<option value="' . $keuze . '">' . $keuze;
                    }
                    $html .= '</select>';
                    break;
                case 'veel_tekst':
                    $html .= '<dd><textarea id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . '></textarea>';
                    break;
                default:
                    $html .= '<dd><input id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . '>';
            }
            //$html .= print_r($field, true); //DEBUG
        }
        $html .= '<input type="submit" value="' . __('Send', $this::TEXT_DOMAIN) . '">';
        $html .= '</dl>';
        $html .= '</form>';
        $html .= '<script>jQuery(document).ready(function(){
    jQuery("#recras-form' . $formID . '").on("submit", function(e){
        e.preventDefault();
        return submitRecrasForm(' . $formID . ', "' . $subdomain . '", "' . plugins_url('/submit.php', __FILE__) . '");
    });
});</script>';

        return $html;
    }

    public function generateProgramme($programme, $startTime = '00:00', $showHeader = true)
    {
        $html = '<table class="recras-programme">';

        if ($showHeader) {
            $html .= '<thead>';
            $html .= '<tr><th>' . __('From', $this::TEXT_DOMAIN) . '<th>' . __('Until', $this::TEXT_DOMAIN) . '<th>' . __('Activity', $this::TEXT_DOMAIN);
            $html .= '</thead>';
        }

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
            $class = (!is_null($lastTime) && $startFormatted < $lastTime) ? ' class="recras-new-day"' : '';

            $html .= '<tr' . $class . '><td>' . $startFormatted;
            $html .= '<td>' . $endDate->add($timeEnd)->format('H:i');
            $html .= '<td>' . $activity->omschrijving;
            $lastTime = $startFormatted;
        }
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    public function loadScripts()
    {
        wp_register_script('recras', plugins_url('/js/recras.js', __FILE__), ['jquery'], '0.5.0', true);
        wp_localize_script('recras', 'recras_l10n', [
            'sent_success' => __('Your message was sent successfully', $this::TEXT_DOMAIN),
            'sent_error' => __('There was an error sending your message', $this::TEXT_DOMAIN),
        ]);
        wp_enqueue_script('recras');
    }

    public function returnPrice($price)
    {
        $currency = get_option('recras_currency');
        return '<span class="recras-price">' . $currency . ' ' . $price . '</span>';
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

    public function showActivationNotice()
    {
        global $pagenow;

        if ($pagenow === 'plugins.php' && !extension_loaded('curl')) {
            echo '<div class="update-nag notice is-dismissible">' . __('The cURL extension for PHP is not installed. Without this, submitting contact forms will not work.', $this::TEXT_DOMAIN) . '</div>';
        }
    }
}
$recras = new Plugin;
