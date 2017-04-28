<?php
if (!defined('ABSPATH'))
    exit;

if (!class_exists('_WP_Editors'))
    require(ABSPATH . WPINC . '/class-wp-editor.php');


function recrasPluginTranslation()
{
    $strings = [
        'get_arrangement_id' => __('What is the ID of the package?', \Recras\Plugin::TEXT_DOMAIN),
        'get_contact_id' => __('What is the ID of the contact form?', \Recras\Plugin::TEXT_DOMAIN),
        'id_positive' => __('ID should be a positive integer', \Recras\Plugin::TEXT_DOMAIN),
    ];

    return 'tinyMCE.addI18n("' . \_WP_Editors::$mce_locale . '.recras", ' . json_encode($strings) . ");\n";
}

$strings = recrasPluginTranslation();
