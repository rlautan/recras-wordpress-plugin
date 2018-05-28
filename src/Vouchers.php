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

        $redirect = '';
        if (isset($attributes['redirect'])) {
            $redirect = "redirect_url: '" . $attributes['redirect'] . "',";
        }

        $generatedDivID = uniqid('V');

        $plugin = new Plugin();
        return "
<div id='" . $generatedDivID . "'></div>
<script src='" . $plugin->baseUrl . '/js/onlinebooking.js?v=0.4.0' . "'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var voucherOptions = new RecrasOptions({
            recras_hostname: '" . $subdomain . ".recras.nl',
            element: document.getElementById('" . $generatedDivID . "'),
            locale: '" . Settings::externalLocale() . "',
            " . $redirect . "
        });
        new RecrasVoucher(voucherOptions);
    });
</script>";
    }


    /**
     * Show the TinyMCE shortcode generator product form
     */
    public static function showForm()
    {
        require_once(dirname(__FILE__) . '/../editor/form-vouchers.php');
    }
}
