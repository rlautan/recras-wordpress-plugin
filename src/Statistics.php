<?php
namespace Recras;

class Statistics
{
    const EVENT_NAME = 'recras_report_statistics';

    public static function adminNoticeOptIn()
    {
        ?>
        <div class="notice notice-info is-dismissible">
            <form action="<?= admin_url('admin-post.php?action=optin_statistics'); ?>" method="POST">
                <p><?php _e('Recras would like your permission to share the following statistics with our developers:', Plugin::TEXT_DOMAIN); ?></p>
                <ul class="ul-disc">
                    <li><?php _e('WordPress version'); ?>
                    <li><?php _e('Recras plugin version'); ?>
                    <li><?php _e('Your Recras URL (i.e. demo.recras.nl, so we know which of our members use the plugin)'); ?>
                    <li><?php _e('PHP version (the underlying software your site runs on)'); ?>
                    <li><?php _e('A unique ID that <strong>cannot</strong> be traced back to you, to keep track of changes through time'); ?>
                </ul>
                <p><?php _e('Sharing this information will help us with the development of our plugin!', Plugin::TEXT_DOMAIN); ?></p>
                <p>
                    <button class="button button-primary"><?php _e('I consent to sharing these statistics'); ?></button>
                </p>
            </form>
        </div>
        <?php
    }

    public static function enableOptIn()
    {
        update_option('recras_statistics_optin', true);

        header('Location: ' . admin_url('admin.php?page=recras&msg=optinthanks'));
        exit;
    }

    public static function sendReport()
    {
        global $wp_version;

        $payload = json_encode([
            'phpVersion' => phpversion(),
            'pluginVersion' => Plugin::PLUGIN_VERSION,
            'subdomain' => get_option('recras_subdomain'),
            'uuid' => get_option('recras_uuid'),
            'wpVersion' => $wp_version,
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
