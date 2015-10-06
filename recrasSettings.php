<?php
namespace Recras;

class Settings
{
    public static function addInputSubdomain($args)
    {
        $field = $args['field'];
        $value = get_option($field);

        printf('<input type="text" name="%s" id="%s" value="%s">', $field, $field, $value);
    }

    public static function editSettings()
    {
        if (!current_user_can('manage_options'))
        {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        require_once('admin/settings.php');
    }

    public static function registerSettings()
    {
        add_settings_section(
            'recras',
            'Recras Settings',
            ['Recras\Settings', 'settingsHelp'],
            'recras'
        );

        register_setting('recras', 'recras_subdomain', ['Recras\Plugin', 'sanitizeSubdomain']);

        add_settings_field('recras_subdomain', 'Subdomain', ['Recras\Settings', 'addInputSubdomain'], 'recras', 'recras', ['field' => 'recras_subdomain']);
        //recras_subdomain
    }

    public static function settingsHelp()
    {
        _e('Enter your Recras details here', Plugin::TEXT_DOMAIN);
    }
}
