<?php
namespace Recras;

class ContactForm
{
    const ELEMENTS = ['dl', 'table', 'ol'];

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
        
        $showLabel = true;
        if (isset($attributes['showlabels']) && ($attributes['showlabels'] == 'false' || $attributes['showlabels'] == 0 || $attributes['showlabels'] == 'no')) {
            $showLabel = false;
        }
        
        $showPlaceholder = true;
        if (isset($attributes['showplaceholders']) && ($attributes['showplaceholders'] == 'false' || $attributes['showplaceholders'] == 0 || $attributes['showplaceholders'] == 'no')) {
            $showPlaceholder = false;
        }

        $element = 'dl';
        if (isset($attributes['element']) && in_array($attributes['element'], self::ELEMENTS)) {
            $element = $attributes['element'];
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

        return self::generateForm($attributes['id'], $formTitle, $formFields, $element, $subdomain, $arrangementID, $showLabel, $showPlaceholder);
    }

    private static function generateEndTag($element)
    {
        return '</' . $element . '>';
    }

    public static function generateForm($formID, $formTitle, $formFields, $containerElement, $subdomain, $arrangementID, $showLabel, $showPlaceholder)
    {
        $arrangementen = [];

        $html  = '';
        if ($formTitle) {
            $html .= '<h2>' . $formTitle . '</h2>';
        }

        $html .= '<form class="recras-contact" id="recras-form' . $formID . '">';
        $html .= self::generateStartTag($containerElement);
        foreach ($formFields as $field) {
            if ($field->soort_invoer !== 'header' && ($field->soort_invoer !== 'boeking.arrangement' || is_null($arrangementID))) {
                $html .= self::generateLabel($containerElement, $field, $showLabel);
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
                        $html .= self::generateSubTag($containerElement) . self::generateSelect($field, $arrangementen);
                    } else {
                        $html .= self::generateInput($field, [
                            'placeholder' => $showPlaceholder,
                            'type' => 'hidden',
                            'value' => $arrangementID,
                        ]);
                    }
                    break;
                case 'boeking.datum':
                    $html .= self::generateSubTag($containerElement) . self::generateInput($field, [
                            'pattern' => '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])',
                            'placeholder' => 'yyyy-mm-dd',
                            'type' => 'date',
                        ]);
                    break;
                case 'boeking.groepsgrootte':
                    $html .= self::generateSubTag($containerElement) . self::generateInput($field, [
                            'placeholder' => $showPlaceholder,
                            'raw' => [
                                'min' => 1,
                            ],
                            'type' => 'number',
                        ]);
                    break;
                case 'boeking.starttijd':
                    $html .= self::generateSubTag($containerElement) . self::generateInput($field, [
                            'pattern' => '(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])',
                            'placeholder' => 'hh:mm',
                            'type' => 'time',
                        ]);
                    break;
                case 'contactpersoon.geslacht':
                    $html .= self::generateSubTag($containerElement) . self::generateSelect($field, [
                        'man' => __('Male', Plugin::TEXT_DOMAIN),
                        'vrouw' => __('Female', Plugin::TEXT_DOMAIN),
                        'onbekend' => __('Unknown', Plugin::TEXT_DOMAIN),
                    ]);
                    break;
                case 'header':
                    if (strpos($html, '<dt') !== false) { //FIXME
                        $html .= self::generateEndTag($containerElement);
                    }
                    $html .= '<h3>' . $field->naam . '</h3>';
                    if (strpos($html, '<dt') !== false) { //FIXME
                        $html .= self::generateStartTag($containerElement);
                    }
                    break;
                case 'keuze':
                    $keuzes = [];
                    foreach ($field->mogelijke_keuzes as $keuze) {
                        $keuzes[$keuze] = $keuze;
                    }
                    $html .= self::generateSubTag($containerElement) . self::generateSelect($field, $keuzes);
                    break;
                case 'veel_tekst':
                    $html .= self::generateSubTag($containerElement) . '<textarea id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . '></textarea>';
                    break;
                default:
                    $html .= self::generateSubTag($containerElement) . self::generateInput($field, [
                            'placeholder' => $showPlaceholder,
                        ]);
            }
            //$html .= print_r($field, true); //DEBUG
        }
        $html .= self::generateEndTag($containerElement);
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

    private static function generateInput($field, $options = [])
    {
        $options = array_merge([
            'pattern' => null,
            'placeholder' => false,
            'raw' => [],
            'required' => false,
            'type' => 'text',
            'value' => '',
        ], $options);

        $pattern = ($options['pattern'] ? ' pattern="' . $options['pattern'] . '"' : '');
        if (is_string($options['placeholder'])) {
            $placeholder = ' placeholder="' . $options['placeholder'] . '"';
        } elseif ($options['placeholder']) {
            $placeholder = ' placeholder="' . htmlentities($field->naam, ENT_COMPAT | ENT_HTML5) . '"';
        } else {
            $placeholder = '';
        }
        $required = ($field->verplicht ? ' required' : '');

        $raw = '';
        foreach ($options['raw'] as $rawKey => $rawValue) {
            $raw .= ' ' . $rawKey . '="' . $rawValue . '"';
        }

        return '<input id="field' . $field->id . '" type="' . $options['type'] . '" name="' . $field->field_identifier . '" value="' . $options['value'] . '"' . $required . $placeholder . $pattern . $raw . '>';
    }


    private static function generateLabel($mainElement, $field, $showLabel)
    {
        $html = '';
        switch ($mainElement) {
            case 'dl':
                $html .= '<dt>';
                break;
            case 'ol':
                $html .= '<li>';
                break;
            case 'table':
                $html .= '<tr><td>';
                break;
        }
        if ($showLabel) {
            $html .= '<label for="field' . $field->id . '">' . $field->naam . '</label>';
        }
        return $html;
    }

    public static function generateSelect($field, $options)
    {
        $html = '<select id="field' . $field->id . '" name="' . $field->field_identifier . '"' . ($field->verplicht ? ' required' : '') . '>';
        foreach ($options as $value => $name) {
            $html .= '<option value="' . $value . '">' . $name;
        }
        $html .= '</select>';
        return $html;
    }

    private static function generateStartTag($element)
    {
        return '<' . $element . '>';
    }

    private static function generateSubTag($mainElement)
    {

        $html = '';
        switch ($mainElement) {
            case 'dl':
                $html .= '<dd>';
                break;
            case 'ol':
                break;
            case 'table':
                $html .= '<td>';
                break;
        }

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
