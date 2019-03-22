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
        if (isset($attributes['id']) && !ctype_digit($attributes['id']) && !is_int($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }

        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return Plugin::getNoSubdomainError();
        }

        $extraOptions = '';
        if (isset($attributes['id'])) {
            $extraOptions .= "voucher_template_id: " . $attributes['id'] . ",\n";
        }

        if (isset($attributes['redirect'])) {
            $extraOptions .= "redirect_url: '" . $attributes['redirect'] . "',\n";
        }

        if (Analytics::useAnalytics()) {
            $extraOptions .= "analytics: true,\n";
        }

        $generatedDivID = uniqid('V');

        return "
<div id='" . $generatedDivID . "'></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var voucherOptions = new RecrasOptions({
            recras_hostname: '" . $subdomain . ".recras.nl',
            element: document.getElementById('" . $generatedDivID . "'),
            locale: '" . Settings::externalLocale() . "',
            " . $extraOptions . "
        });
        new RecrasVoucher(voucherOptions);
    });
</script>";
    }


    /**
     * Clear voucher template cache (transients)
     */
    public static function clearCache()
    {
        global $recrasPlugin;

        $subdomain = get_option('recras_subdomain');
        $error = $recrasPlugin->transients->delete($subdomain . '_voucher_templates');

        header('Location: ' . admin_url('admin.php?page=recras-clear-cache&msg=' . Plugin::getStatusMessage($error)));
        exit;
    }


    public function getTemplates($subdomain)
    {
        global $recrasPlugin;

        $json = $recrasPlugin->transients->get($subdomain . '_voucher_templates');
        if ($json === false) {
            try {
                $json = Http::get($subdomain, 'voucher_templates');
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            $recrasPlugin->transients->set($subdomain . '_voucher_templates', $json);
        }

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
