<?php
namespace Recras;


class Availability
{
    /**
     * Add the [recras-availability] shortcode
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function addAvailabilityShortcode($attributes)
    {
        if (!isset($attributes['id'])) {
            return __('Error: no ID set', Plugin::TEXT_DOMAIN);
        }
        if (!ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }


        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return Plugin::getNoSubdomainError();
        }


        $json = get_transient('recras_' . $subdomain . '_arrangement_' . $attributes['id']);
        if ($json === false) {
            try {
                $json = Http::get($subdomain, 'arrangementen/' . $attributes['id']);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
            set_transient('recras_' . $subdomain . '_arrangement_' . $attributes['id'], $json, 86400);
        }


        $url = 'https://' . $subdomain . '.recras.nl/api/arrangementbeschikbaarheid/id/' . $attributes['id'];
        $iframeUID = uniqid('rpai'); // Recras Package Availability Iframe
        $html = '';
        $html .= '<iframe src="' . $url . '" style="width:100%;height:200px" frameborder=0 scrolling="auto" id="' . $iframeUID . '"></iframe>';
        /*$html .= <<<SCRIPT
<script>
    window.addEventListener('message', function(e) {
        console.log(e.data);
        var origin = e.origin || e.originalEvent.origin;
        if (origin.match(/{$subdomain}\.recras\.nl/)) {
            document.getElementById('{$iframeUID}').style.height = e.data.iframeHeight + 'px';
        }
    });
</script>
SCRIPT;*/
        return $html;
    }


    /**
     * Show the TinyMCE shortcode generator arrangement form
     */
    public static function showForm()
    {
        require_once(dirname(__FILE__) . '/../editor/form-package-availability.php');
    }
}
