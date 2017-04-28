<?php
namespace Recras;


class Arrangement
{
    /**
     * Add the [recras-arrangement] shortcode
     *
     * @param array $attributes
     *
     * @return string
     */
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


        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return Plugin::getNoSubdomainError();
        }


        $json = get_transient('recras_' . $subdomain . '_arrangement_' . $attributes['id']);
        if ($json === false) {
            try {
                $json = Http::get($subdomain, 'arrangementen/' . $attributes['id']);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            set_transient('recras_' . $subdomain . '_arrangement_' . $attributes['id'], $json, 86400);
        }


        switch ($attributes['show']) {
            case 'description':
                return $json->uitgebreide_omschrijving;
            case 'duration':
                return self::getDuration($json);
            case 'image_url':
                return $json->image_filename;
            case 'location':
                return self::getLocation($json);
            case 'persons':
                return '<span class="recras-persons">' . $json->aantal_personen . '</span>';
            case 'price_pp_excl_vat':
                return Price::format($json->prijs_pp_exc);
            case 'price_pp_incl_vat':
                return Price::format($json->prijs_pp_inc);
            case 'price_total_excl_vat':
                return Price::format($json->prijs_totaal_exc);
            case 'price_total_incl_vat':
                return Price::format($json->prijs_totaal_inc);
            case 'program':
            case 'programme':
                $startTime = (isset($attributes['starttime']) ? $attributes['starttime'] : '00:00');
                $showHeader = !isset($attributes['showheader']) || Settings::parseBoolean($attributes['showheader']);
                return self::generateProgramme($json->programma, $startTime, $showHeader);
            case 'title':
                $title = $json->weergavenaam;
                if ($title === '') {
                    $title = $json->arrangement;
                }
                return '<span class="recras-title">' . $title . '</span>';
            default:
                return __('Error: unknown option', Plugin::TEXT_DOMAIN);
        }
    }


    public static function addArrangementShortcodeOld($attributes)
    {
        error_log('Notice: [arrangement] is deprecated, please use [recras-arrangement] instead!');
        return self::addArrangementShortcode($attributes);
    }


    /**
     * Clear arrangement cache (transients)
     */
    public static function clearCache()
    {
        $subdomain = get_option('recras_subdomain');
        $errors = 0;

        $arrangements = array_keys(self::getArrangements($subdomain));
        foreach ($arrangements as $id) {
            $errors += Plugin::deleteTransient('recras_' . $subdomain . '_arrangement_' . $id);
        }
        $errors += Plugin::deleteTransient('recras_' . $subdomain . '_arrangements');

        header('Location: ' . admin_url('admin.php?page=recras-clear-cache&msg=' . Plugin::getStatusMessage($errors)));
        exit;
    }


    /**
     * Generate the programme for an arrangement
     *
     * @param array $programme
     * @param string $startTime
     * @param bool $showHeader
     *
     * @return string
     */
    public static function generateProgramme($programme, $startTime = '00:00', $showHeader = true)
    {
        $html = '<table class="recras-programme">';

        if ($showHeader) {
            $html .= '<thead>';
            $html .= '<tr><th>' . __('From', Plugin::TEXT_DOMAIN) . '<th>' . __('Until', Plugin::TEXT_DOMAIN) . '<th>' . __('Activity', Plugin::TEXT_DOMAIN);
            $html .= '</thead>';
        }

        // Calculate how many days this programme spans - begin and eind are ISO8601 periods/intervals
        $startDatetime = new \DateTime('00:00');
        $startDatetime->add(new \DateInterval($programme[0]->begin));
        $endDatetime = new \DateTime('00:00');
        $endDatetime->add(new \DateInterval($programme[count($programme) - 1]->eind));
        $isMultiDay = ($endDatetime->diff($startDatetime)->d > 0);

        $html .= '<tbody>';
        $lastTime = null;
        $day = 0;

        foreach ($programme as $activity) {
            if (!$activity->omschrijving) {
                continue;
            }
            $startDate = new \DateTime($startTime);
            $endDate = new \DateTime($startTime);
            $timeBegin = new \DateInterval($activity->begin);
            $timeEnd = new \DateInterval($activity->eind);
            $startFormatted = $startDate->add($timeBegin)->format('H:i');
            if ($isMultiDay && (is_null($lastTime) || $startFormatted < $lastTime)) {
                ++$day;
                $html .= '<tr class="recras-new-day"><th colspan="3">' . sprintf(__('Day %d', Plugin::TEXT_DOMAIN), $day);
            }

            $html .= '<tr><td>' . $startFormatted;
            $html .= '<td>' . $endDate->add($timeEnd)->format('H:i');
            $html .= '<td>' . $activity->omschrijving;
            $lastTime = $startFormatted;
        }
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }


    /**
     * Get arrangements from the Recras API
     *
     * @param string $subdomain
     * @param bool $onlyOnline
     *
     * @return array|string
     */
    public static function getArrangements($subdomain, $onlyOnline = false)
    {
        $json = get_transient('recras_' . $subdomain . '_arrangements');
        if ($json === false) {
            try {
                $json = Http::get($subdomain, 'arrangementen');
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            set_transient('recras_' . $subdomain . '_arrangements', $json, 86400);
        }

        $arrangements = [
            0 => (object) ['arrangement' => ''],
        ];
        foreach ($json as $arrangement) {
            if (!$onlyOnline || $arrangement->mag_online) {
                $arrangements[$arrangement->id] = $arrangement;
            }
        }
        return $arrangements;
    }


    /**
     * Get arrangements for a certain contact form from the Recras API
     *
     * @param string $subdomain
     * @param int $contactformID
     *
     * @return array|string
     */
    public function getArrangementsForContactForm($subdomain, $contactformID)
    {
        $json = get_transient('recras_' . $subdomain . '_contactform_' . $contactformID . '_arrangements');
        if ($json === false) {
            try {
                $json = Http::get($subdomain, 'contactformulieren/' . $contactformID . '/arrangementen');
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            set_transient('recras_' . $subdomain . '_contactform_' . $contactformID . '_arrangements', $json, 86400);
        }
        if ($json === []) {
            return [];
        }

        $arrangements = [
            0 => '',
        ];
        foreach ($json as $arrangement) {
            $arrangements[$arrangement->arrangement_id] = $arrangement->Arrangement->arrangement;
        }
        natcasesort($arrangements);
        return $arrangements;
    }


    /**
     * Get duration of a package
     *
     * @param object $json
     *
     * @return string
     */
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
            $durations[] = str_pad($duration->i, 2, '0', STR_PAD_LEFT);
        } else {
            $durations[] = '00';
        }
        if (empty($durations)) {
            $html .= __('No duration specified', Plugin::TEXT_DOMAIN);
        } else {
            $html .= implode(':', $durations);
        }
        $html .= '</span>';

        return $html;
    }


    /**
     * Get the starting location of a package
     *
     * @param object $json
     *
     * @return string
     */
    private static function getLocation($json)
    {
        if ($json->ontvangstlocatie) {
            $location = $json->ontvangstlocatie;
        } else {
            $location = __('No location specified', Plugin::TEXT_DOMAIN);
        }
        return '<span class="recras-location">' . $location . '</span>';
    }


    /**
     * Get all valid options for the "show" argument
     *
     * @return array
     */
    public static function getValidOptions()
    {
        return ['description', 'duration', 'image_url', 'location', 'persons', 'price_pp_excl_vat', 'price_pp_incl_vat', 'price_total_excl_vat', 'price_total_incl_vat', 'program', 'programme', 'title'];
    }


    /**
     * Show the TinyMCE shortcode generator package form
     */
    public static function showForm()
    {
        require_once(dirname(__FILE__) . '/../editor/form-arrangement.php');
    }
}
