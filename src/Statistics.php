<?php
namespace Recras;

class Statistics
{
    const EVENT_NAME = 'recras_report_statistics';

    public static function sendReport()
    {
        $payload = json_encode([
            'phpVersion' => phpversion(),
            'pluginVersion' => Plugin::PLUGIN_VERSION,
            'siteUrl' => get_site_url(),
            'subdomain' => get_option('recras_subdomain'),
        ]);

        $ch = curl_init('');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public static function scheduleReport()
    {
        if (!wp_next_scheduled(self::EVENT_NAME)) {
            wp_schedule_event(time(), 'daily', self::EVENT_NAME);
        }
    }
}
