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
        if (isset($attributes['id']) && !ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }

        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return Plugin::getNoSubdomainError();
        }

        $templateText = '';
        if ($attributes['id']) {
            $templateText = "voucher_template_id: " . $attributes['id'] . ",";
        }

        $redirect = '';
        if (isset($attributes['redirect'])) {
            $redirect = "redirect_url: '" . $attributes['redirect'] . "',";
        }

        $generatedDivID = uniqid('V');

        $plugin = new Plugin();
        return "
<div id='" . $generatedDivID . "'></div>
<script src='" . $plugin->baseUrl . '/js/onlinebooking.js?v=0.5.1' . "'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var voucherOptions = new RecrasOptions({
            recras_hostname: '" . $subdomain . ".recras.nl',
            element: document.getElementById('" . $generatedDivID . "'),
            locale: '" . Settings::externalLocale() . "',
            " . $templateText . "
            " . $redirect . "
        });
        new RecrasVoucher(voucherOptions);
    });
</script>";
    }


    public function getTemplates($subdomain)
    {
        $json = Http::get($subdomain, 'voucher_templates');
        $templates = [];
        foreach ($json as $template) {
            if ($template->contactform_id) {
                $templates[$template->id] = $template;
            }
        }
        return $templates;
    }


    /**
     * Show the TinyMCE shortcode generator product form
     */
    public static function showForm()
    {
        require_once(dirname(__FILE__) . '/../editor/form-vouchers.php');
    }
}
