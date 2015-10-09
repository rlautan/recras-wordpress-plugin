<?php
namespace Recras;

class ContactForm
{
    public static function addContactShortcode($attributes)
    {
        if (!isset($attributes['id'])) {
            return __('Error: no ID set', Plugin::TEXT_DOMAIN);
        }
        if (!ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }

        $subdomain = get_option('recras_subdomain');
        if (!$subdomain) {
            return __('Error: you have not set your Recras subdomain yet', Plugin::TEXT_DOMAIN);
        }


        // Get basic info for the form
        $baseUrl = 'https://' . $subdomain . '.recras.nl/api2.php/contactformulieren/' . $attributes['id'];
        $json = @file_get_contents($baseUrl);
        if ($json === false) {
            return __('Error: could not retrieve external data', Plugin::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', Plugin::TEXT_DOMAIN);
        }
        $formTitle = $json->naam;

        if (isset($attributes['showtitle']) && ($attributes['showtitle'] == 'false' || $attributes['showtitle'] == 0 || $attributes['showtitle'] == 'no')) {
            $formTitle = false;
        }


        // Get fields for the form
        $json = @file_get_contents($baseUrl . '/velden');
        if ($json === false) {
            return __('Error: could not retrieve external data', Plugin::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', Plugin::TEXT_DOMAIN);
        }
        $formFields = $json;


        $arrangementID = null;
        if (isset($attributes['arrangement'])) {
            $arrangementID = (int) $attributes['arrangement'];
        }

        return self::generateForm($attributes['id'], $formTitle, $formFields, $subdomain, $arrangementID);
    }

    public static function generateForm($formID, $formTitle, $formFields, $subdomain, $arrangementID)
    {
        $arrangementen = [];

        $html  = '';
        if ($formTitle) {
            $html .= '<h2>' . $formTitle . '</h2>';
        }

        $html .= '<form class="recras-contact" id="recras-form' . $formID . '">';
        $html .= '<dl>';
        foreach ($formFields as $field) {
            if ($field->soort_invoer !== 'header' && ($field->soort_invoer !== 'boeking.arrangement' || is_null($arrangementID))) {
                $html .= '<dt><label for="field' . $field->id . '">' . $field->naam . '</label>';
                if ($field->verplicht) {
                    $html .= '<span class="recras-required">*</span>';
                }
            }
            switch ($field->soort_invoer) {
                case 'boeking.arrangement':
                    if (is_null($arrangementID)) {
                        if (empty($arrangementen)) {
                            $classArrangement = new Arrangement;
                            $arrangementen = $classArrangement->getArrangements($subdomain);
                        }
                        $html .= self::generateSelect($field, $arrangementen);
                    } else {
                        $html .= '<input type="hidden" name="' . $field->field_identifier . '" value="' . $arrangementID . '">';
                    }
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
                    $html .= self::generateSelect($field, [
                        'man' => __('Male', Plugin::TEXT_DOMAIN),
                        'vrouw' => __('Female', Plugin::TEXT_DOMAIN),
                        'onbekend' => __('Unknown', Plugin::TEXT_DOMAIN),
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
        $html .= '<input type="submit" value="' . __('Send', Plugin::TEXT_DOMAIN) . '">';
        $html .= '</form>';
        $html .= '<script>jQuery(document).ready(function(){
    jQuery("#recras-form' . $formID . '").on("submit", function(e){
        e.preventDefault();
        return submitRecrasForm(' . $formID . ', "' . $subdomain . '", "' . plugins_url('/', __FILE__) . '");
    });
});</script>';

        return $html;
    }

    public static function generateSelect($field, $options)
    {
        $html = '<dd><select id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . '>';
        foreach ($options as $value => $name) {
            $html .= '<option value="' . $value . '">' . $name;
        }
        $html .= '</select>';
        return $html;
    }

    public function getForms($subdomain)
    {
        $baseUrl = 'https://' . $subdomain . '.recras.nl/api2.php/contactformulieren';
        $json = @file_get_contents($baseUrl);
        if ($json === false) {
            return __('Error: could not retrieve external data', Plugin::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', Plugin::TEXT_DOMAIN);
        }

        $forms = [];
        foreach ($json as $form) {
            $forms[$form->id] = $form->naam;
        }
        return $forms;
    }
}
