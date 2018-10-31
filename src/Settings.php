<?php
namespace Recras;

class Settings
{
    /**
     * Add a currency input field
     *
     * @param array $args
     */
    public static function addInputCurrency($args)
    {
        $field = $args['field'];
        $value = get_option($field);
        if (!$value) {
            $value = '€';
        }

        printf('<input type="text" name="%s" id="%s" value="%s">', $field, $field, $value);
    }


    /**
     * Add a date/time picker option
     *
     * @param array $args
     */
    public static function addInputDateTimePicker($args)
    {
        $field = $args['field'];
        $value = get_option($field);

        printf('<input type="checkbox" name="%s" id="%s" value="1"%s>', $field, $field, ($value ? ' checked' : ''));
    }


    /**
     * Add a decimal separator input field
     *
     * @param array $args
     */
    public static function addInputDecimal($args)
    {
        $field = $args['field'];
        $value = get_option($field);
        if (!$value) {
            $value = '.';
        }

        printf('<input type="text" name="%s" id="%s" value="%s" size="2" maxlength="1">', $field, $field, $value);
    }


    /**
     * Add a subdomain input field
     *
     * @param array $args
     */
    public static function addInputSubdomain($args)
    {
        $field = $args['field'];
        $value = get_option($field);
        if (!$value) {
            $value = 'demo';
        }

        printf('<input type="text" name="%s" id="%s" value="%s">.recras.nl', $field, $field, $value);
    }


    public static function addInputTheme($args)
    {
        $themes = self::getThemes();

        $field = $args['field'];
        $value = get_option($field);
        if (!$value) {
            $value = 'none';
        }

        $html = '<select name="' . $field . '" id="' . $field . '">';
        foreach ($themes as $key => $theme) {
            $selText = '';
            if ($value === $key) {
                $selText = ' selected';
            }
            $html .= '<option value="' . $key . '"' . $selText . '>' . $theme['name'];
        }
        $html .= '</select>';
        echo $html;
    }


    public static function clearCache()
    {
        if (!current_user_can('edit_pages')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        require_once('admin/cache.php');
    }


    /**
     * Load the admin options page
     */
    public static function editSettings()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        require_once('admin/settings.php');
    }


    /**
     * This returns a valid locale, based on the locale set for WordPress, to use in "external" Recras scripts
     *
     * @return string
     */
    public static function externalLocale()
    {
        $localeShort = substr(get_locale(), 0, 2);
        switch ($localeShort) {
            case 'de':
                return 'de_DE';
            case 'fy':
            case 'nl':
                return 'nl_NL';
            default:
                return 'en_GB';
        }
    }


    /**
     * Get the Recras subdomain, which can be set in the shortcode attributes or as global setting
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function getSubdomain($attributes)
    {
        if (isset($attributes['recrasname'])) {
            return $attributes['recrasname'];
        } else {
            return get_option('recras_subdomain');
        }
    }


    public static function getThemes()
    {
        return [
            'none' => [
                'name' => __('No theme', Plugin::TEXT_DOMAIN),
                'version' => null,
            ],
            'basic' => [
                'name' => __('Basic theme', Plugin::TEXT_DOMAIN),
                'version' => '2.0.0',
            ],
            'recrasblue' => [
                'name' => __('Recras Blue', Plugin::TEXT_DOMAIN),
                'version' => '2.0.0',
            ],
        ];
    }


    /**
     * Parse a boolean value
     *
     * @param string $value
     *
     * @return bool
     */
    public static function parseBoolean($value)
    {
        $bool = true;
        if (isset($value) && ($value == 'false' || $value == 0 || $value == 'no')) {
            $bool = false;
        }
        return $bool;
    }


    /**
     * Register plugin settings
     */
    public static function registerSettings()
    {
        add_settings_section(
            'recras',
            __('Recras settings', Plugin::TEXT_DOMAIN),
            ['Recras\Settings', 'settingsHelp'],
            'recras'
        );

        register_setting('recras', 'recras_subdomain', ['Recras\Settings', 'sanitizeSubdomain']);
        register_setting('recras', 'recras_currency', '');
        register_setting('recras', 'recras_decimal', '');
        register_setting('recras', 'recras_datetimepicker', '');
        register_setting('recras', 'recras_theme', '');

        add_settings_field('recras_subdomain', __('Recras name', Plugin::TEXT_DOMAIN), ['Recras\Settings', 'addInputSubdomain'], 'recras', 'recras', ['field' => 'recras_subdomain']);
        add_settings_field('recras_currency', __('Currency symbol', Plugin::TEXT_DOMAIN), ['Recras\Settings', 'addInputCurrency'], 'recras', 'recras', ['field' => 'recras_currency']);
        add_settings_field('recras_decimal', __('Decimal separator', Plugin::TEXT_DOMAIN), ['Recras\Settings', 'addInputDecimal'], 'recras', 'recras', ['field' => 'recras_decimal']);
        add_settings_field('recras_datetimepicker', __('Use date/time picker script', Plugin::TEXT_DOMAIN), ['Recras\Settings', 'addInputDateTimePicker'], 'recras', 'recras', ['field' => 'recras_datetimepicker']);
        add_settings_field('recras_theme', __('Theme for online booking', Plugin::TEXT_DOMAIN), ['Recras\Settings', 'addInputTheme'], 'recras', 'recras', ['field' => 'recras_theme']);
    }


    /**
     * Sanitize user inputted subdomain
     *
     * @param string $subdomain
     *
     * @return bool|string
     */
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


    /**
     * Echo settings helper text
     */
    public static function settingsHelp()
    {
        _e('Enter your Recras details here', Plugin::TEXT_DOMAIN);
    }
}
