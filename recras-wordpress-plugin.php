<?php
namespace Recras;

require_once('Settings.php');
require_once('Arrangement.php');

// Debugging
if (WP_DEBUG) {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

/**
 * @package Recras WordPress Plugin
 * @version 0.8.0
 */
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: http://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Version: 0.8.0
Author URI: http://www.recras.nl/
*/

class Plugin
{
    const TEXT_DOMAIN = 'recras-wp';


    public function __construct()
    {
        // Init Localisation
        load_default_textdomain();
        load_plugin_textdomain($this::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/lang');

        // Add admin menu pages
        add_action('admin_menu', [&$this, 'addMenuItems']);

        add_action('admin_init', ['Recras\Settings', 'registerSettings']);

        add_action('admin_notices', [&$this, 'showActivationNotice']);

        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);

        $this->addShortcodes();
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
        add_shortcode('arrangement', ['Recras\Arrangement', 'addArrangementShortcode']);
        add_shortcode('recras-contact', [$this, 'addContactShortcode']);
    }

    public function generateForm($formID, $formTitle, $formFields, $subdomain)
    {
        $arrangementen = [];

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
                case 'boeking.arrangement':
                    if (empty($arrangementen)) {
                        $arrangementen = $this->getArrangements($subdomain);
                    }
                    $html .= $this->generateSelect($field, $arrangementen);
                    break;
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
                    $html .= $this->generateSelect($field, [
                        'man' => __('Male', $this::TEXT_DOMAIN),
                        'vrouw' => __('Female', $this::TEXT_DOMAIN),
                        'onbekend' => __('Unknown', $this::TEXT_DOMAIN),
                    ]);
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
        $html .= '</dl>';
        $html .= '<input type="submit" value="' . __('Send', $this::TEXT_DOMAIN) . '">';
        $html .= '</form>';
        $html .= '<script>jQuery(document).ready(function(){
    jQuery("#recras-form' . $formID . '").on("submit", function(e){
        e.preventDefault();
        return submitRecrasForm(' . $formID . ', "' . $subdomain . '", "' . plugins_url('/', __FILE__) . '");
    });
});</script>';

        return $html;
    }

    public function generateSelect($field, $options)
    {
        $html = '<dd><select id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . '>';
        foreach ($options as $value => $name) {
            $html .= '<option value="' . $value . '">' . $name;
        }
        $html .= '</select>';
        return $html;
    }

    public function getArrangements($subdomain)
    {
        $baseUrl = 'https://' . $subdomain . '.recras.nl/api2.php/arrangementen';
        $json = @file_get_contents($baseUrl);
        if ($json === false) {
            return __('Error: could not retrieve external data', $this::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', $this::TEXT_DOMAIN);
        }

        $arrangements = [];
        foreach ($json as $arrangement) {
            $arrangements[$arrangement->id] = $arrangement->arrangement;
        }
        $arrangements[0] = '';
        return $arrangements;
    }

    public function loadScripts()
    {
        wp_register_script('recras', plugins_url('/js/recras.js', __FILE__), ['jquery'], '0.7.0', true);
        wp_localize_script('recras', 'recras_l10n', [
            'loading' => __('Loading...', $this::TEXT_DOMAIN),
            'sent_success' => __('Your message was sent successfully', $this::TEXT_DOMAIN),
            'sent_error' => __('There was an error sending your message', $this::TEXT_DOMAIN),
        ]);
        wp_enqueue_script('recras');
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
