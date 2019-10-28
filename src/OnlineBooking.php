<?php
namespace Recras;

class OnlineBooking
{
    /**
     * Add the [recras-booking] shortcode
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function renderOnlineBooking($attributes)
    {
        if (isset($attributes['id']) && !ctype_digit($attributes['id']) && !is_int($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }

        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return Plugin::getNoSubdomainError();
        }

        $arrangementID = isset($attributes['id']) ? $attributes['id'] : null;
        if (!$arrangementID && isset($attributes['get_param'])) {
            $getParam = $attributes['get_param'];
            if (isset($_GET[$getParam])) {
                $arrangementID = $_GET[$getParam];
            }
        }

        $enableResize = !isset($attributes['autoresize']) || (!!$attributes['autoresize'] === true);
        $useNewLibrary = isset($attributes['use_new_library']) ? (!!$attributes['use_new_library']) : false;
        $previewTimes = isset($attributes['show_times']) ? (!!$attributes['show_times']) : false;
        $redirect = isset($attributes['redirect']) ? $attributes['redirect'] : null;

        $preFillAmounts = [];
        if ($arrangementID && isset($attributes['product_amounts'])) {
            $preFillAmounts = json_decode($attributes['product_amounts'], true);
            if (!$preFillAmounts) {
                return __('Error: "product_amounts" is invalid', Plugin::TEXT_DOMAIN);
            }
        }

        if ($useNewLibrary) {
            return self::generateBookingForm($subdomain, $arrangementID, $redirect, $previewTimes, $preFillAmounts);
        }
        return self::generateIframe($subdomain, $arrangementID, $enableResize);
    }


    private static function generateBookingForm($subdomain, $arrangementID, $redirectUrl, $previewTimes, $preFillAmounts)
    {
        $generatedDivID = uniqid('V');
        $extraOptions = '';

        if ($arrangementID) {
            $extraOptions .= "package_id: " . $arrangementID . ",\n";
            $extraOptions .= "autoScroll: false,\n";
        }

        if ($redirectUrl) {
            $extraOptions .= "redirect_url: '" . $redirectUrl . "',\n";
        }

        if (count($preFillAmounts)) {
            $extraOptions .= "productAmounts: " . json_encode($preFillAmounts) . ",\n";
        }

        if (Analytics::useAnalytics()) {
            $extraOptions .= "analytics: true,\n";
        }

        return "
<div id='" . $generatedDivID . "'></div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var bookingOptions = new RecrasOptions({
        recras_hostname: '" . $subdomain . ".recras.nl',
        element: document.getElementById('" . $generatedDivID . "'),
        locale: '" . Settings::externalLocale() . "',
        previewTimes: " . ($previewTimes ? 'true' : 'false') . ",
        " . $extraOptions . "
    });
    new RecrasBooking(bookingOptions);
});
</script>";
    }

    private static function generateIframe($subdomain, $arrangementID, $enableResize)
    {
        $url = 'https://' . $subdomain . '.recras.nl/onlineboeking';
        if ($arrangementID) {
            $url .= '/step1/arrangement/' . $arrangementID;
        }

        $iframeUID = uniqid('robi'); // Recras Online Boeking Iframe
        $html = '';
        $html .= '<iframe src="' . $url . '" style="width:100%;height:450px" frameborder=0 scrolling="auto" id="' . $iframeUID . '"></iframe>';
        if ($enableResize) {
            $html .= <<<SCRIPT
<script>
    window.addEventListener('message', function(e) {
        var origin = e.origin || e.originalEvent.origin;
        if (origin.match(/{$subdomain}\.recras\.nl/)) {
            document.getElementById('{$iframeUID}').style.height = e.data.iframeHeight + 'px';
        }
    });
</script>
SCRIPT;
        }
        return $html;
    }

    /**
     * Show the TinyMCE shortcode generator contact form
     */
    public static function showForm()
    {
        require_once(dirname(__FILE__) . '/../editor/form-booking.php');
    }
}
