<?php
namespace Recras;

class Vouchers
{
    /**
     * Add the [recras-vouchers] shortcode
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function addVoucherShortcode($attributes)
    {
        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return Plugin::getNoSubdomainError();
        }

        $generatedDivID = uniqid('V');

        $locale = Settings::externalLocale();

        $plugin = new Plugin();
        return "
<div id='" . $generatedDivID . "'></div>
<script src='" . $plugin->baseUrl . '/js/onlinebooking.js?v=0.4.0' . "'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var voucherOptions = new RecrasOptions({
            recras_hostname: '" . $subdomain . ".recras.nl',
            element: document.getElementById('" . $generatedDivID . "'),
            locale: '" . $locale . "',
            //redirect_url: 'https://www.onionbooking.com/', // Optional, but recommended
        });
        new RecrasVoucher(voucherOptions);
    });
</script>";
    }
}
