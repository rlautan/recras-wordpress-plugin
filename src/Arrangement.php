<?php
namespace Recras;


class Arrangement
{
    public static function addArrangementShortcode($attributes)
    {
        if (!isset($attributes['id'])) {
            return __('Error: no ID set', Plugin::TEXT_DOMAIN);
        }
        if (!ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }
        if (!isset($attributes['show'])) {
            return __('Error: "show" option not set', Plugin::TEXT_DOMAIN);
        }
        if (!in_array($attributes['show'], self::getValidOptions())) {
            return __('Error: invalid "show" option', Plugin::TEXT_DOMAIN);
        }

        $subdomain = get_option('recras_subdomain');
        if (!$subdomain) {
            return __('Error: you have not set your Recras subdomain yet', Plugin::TEXT_DOMAIN);
        }

        $json = @file_get_contents('https://' . $subdomain . '.recras.nl/api2.php/arrangementen/' . $attributes['id']);
        if ($json === false) {
            return __('Error: could not retrieve external data', Plugin::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', Plugin::TEXT_DOMAIN);
        }

        switch ($attributes['show']) {
            case 'duration':
                return self::getDuration($json);
            case 'location':
                return self::getLocation($json);
            case 'persons':
                return '<span class="recras-persons">' . $json->aantal_personen . '</span>';
            case 'price_pp_excl_vat':
                return self::returnPrice($json->prijs_pp_exc);
            case 'price_pp_incl_vat':
                return self::returnPrice($json->prijs_pp_inc);
            case 'price_total_excl_vat':
                return self::returnPrice($json->prijs_totaal_exc);
            case 'price_total_incl_vat':
                return self::returnPrice($json->prijs_totaal_inc);
            case 'program':
            case 'programme':
                $startTime = (isset($attributes['starttime']) ? $attributes['starttime'] : '00:00');
                $showHeader = true;
                if (isset($attributes['showheader']) && ($attributes['showheader'] == 'false' || $attributes['showheader'] == 0 || $attributes['showheader'] == 'no')) {
                    $showHeader = false;
                }
                return self::generateProgramme($json->programma, $startTime, $showHeader);
            case 'title':
                return '<span class="recras-title">' . $json->arrangement . '</span>';
            default:
                return 'Error: unknown option';
        }
    }

    public static function generateProgramme($programme, $startTime = '00:00', $showHeader = true)
    {
        $html = '<table class="recras-programme">';

        if ($showHeader) {
            $html .= '<thead>';
            $html .= '<tr><th>' . __('From', Plugin::TEXT_DOMAIN) . '<th>' . __('Until', Plugin::TEXT_DOMAIN) . '<th>' . __('Activity', Plugin::TEXT_DOMAIN);
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

    public function getArrangements($subdomain)
    {
        $baseUrl = 'https://' . $subdomain . '.recras.nl/api2.php/arrangementen';
        $json = @file_get_contents($baseUrl);
        if ($json === false) {
            return __('Error: could not retrieve external data', Plugin::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', Plugin::TEXT_DOMAIN);
        }

        $arrangements = [
            0 => '',
        ];
        foreach ($json as $arrangement) {
            $arrangements[$arrangement->id] = $arrangement->arrangement;
        }
        return $arrangements;
    }

    private static function getDuration($json)
    {
        $startTime = new \DateTime('00:00');
        $startTime->add(new \DateInterval($json->programma[0]->begin));

        $endTime = new \DateTime('00:00');
        $endTime->add(new \DateInterval($json->programma[0]->begin));
        $endTime->add(new \DateInterval($json->programma[count($json->programma) - 1]->eind));
        $duration = $startTime->diff($endTime);

        $html  = '<span class="recras-duration">';
        $durations = [];
        if ($duration->d) {
            $durations[] = $duration->d;
        }
        if ($duration->h) {
            $durations[] = $duration->h;
        }
        if ($duration->i) {
            $durations[] = $duration->i;
        }
        if (empty($durations)) {
            $html .= __('No duration specified', Plugin::TEXT_DOMAIN);
        } else {
            $html .= implode(':', $durations);
        }
        $html .= '</span>';

        return $html;
    }

    private static function getLocation($json)
    {
        if ($json->ontvangstlocatie) {
            $location = $json->ontvangstlocatie;
        } else {
            $location = __('No location specified', Plugin::TEXT_DOMAIN);
        }
        return '<span class="recras-location">' . $location . '</span>';
    }

    public static function getValidOptions()
    {
        return ['duration', 'location', 'persons', 'price_pp_excl_vat', 'price_pp_incl_vat', 'price_total_excl_vat', 'price_total_incl_vat', 'program', 'programme', 'title'];
    }

    public static function returnPrice($price)
    {
        $currency = get_option('recras_currency');
        return '<span class="recras-price">' . $currency . ' ' . $price . '</span>';
    }

    public static function showForm()
    {
        require_once(dirname(__FILE__) . '/../editor/form-arrangement.php');
    }
}
