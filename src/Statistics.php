<?php
namespace Recras;

class Statistics
{
    const EVENT_NAME = 'recras_report_statistics';
    const OPTION_NAME = 'recras_statistics_optin';

    const ALLOWED = 'allow';
    const DENIED = 'deny';
    const UNDECIDED = 'undecided';

    public static function adminNoticeOptIn()
    {
        ?>
        <div class="notice notice-info is-dismissible">
            <form action="<?= admin_url('admin-post.php?action=recras_statistics'); ?>" method="POST">
                <p><?php _e('Recras would like your permission to share the following technical data with our developers:', Plugin::TEXT_DOMAIN); ?></p>
                <ul class="ul-disc">
                    <li><?php _e('WordPress version'); ?>
                    <li><?php _e('Recras plugin version'); ?>
                    <li><?php _e('Your Recras URL (i.e. demo.recras.nl, so we know which of our members use the plugin)'); ?>
                    <li><?php _e('Your site URL (i.e. awesomecompany.com, so we know which of our members use the plugin)'); ?>
                    <li><?php _e('PHP version (the underlying software your site runs on)'); ?>
                </ul>
                <p>
                    <?php _e('Sharing this information will help us with the development of our plugin! This information will only be used by the Recras programmers and will not be sold or given away to anyone else.', Plugin::TEXT_DOMAIN); ?>
                    <strong><?php _e('Sharing this information will help us with the development of our plugin! This information will only be used by the Recras programmers and will not be sold or given away to anyone else. No personal information of any kind is being sent.', Plugin::TEXT_DOMAIN); ?></strong>
                </p>
                <p>
                    <button name="consent" value="allow" class="button button-primary"><?php _e('I consent to sharing these statistics'); ?></button>
                    <button name="consent" value="deny" class="button button-secondary"><?php _e('I do not wish to share these statistics'); ?></button>
                </p>
            </form>
        </div>
        <?php
    }

    public static function storeConsent()
    {
        $msg = ($_POST['consent'] === Statistics::ALLOWED) ? Statistics::ALLOWED : Statistics::DENIED;
        update_option(self::OPTION_NAME, $msg);

        header('Location: ' . admin_url('admin.php?page=recras&msg=optin' . $msg));
        exit;
    }

    public static function sendReport()
    {
        global $wp_version;

        $payload = json_encode([
            'phpVersion' => phpversion(),
            'pluginVersion' => Plugin::PLUGIN_VERSION,
            'subdomain' => get_option('recras_subdomain'),
            'siteUrl' => get_site_url(),
            'wpVersion' => $wp_version,
        ]);

        $ch = curl_init(''); //TODO: url
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
