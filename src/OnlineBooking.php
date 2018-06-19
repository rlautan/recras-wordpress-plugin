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
    public static function addBookingShortcode($attributes)
    {
        if (isset($attributes['id']) && !ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }

        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return Plugin::getNoSubdomainError();
        }

        $arrangementID = isset($attributes['id']) ? $attributes['id'] : null;
        $enableResize = !isset($attributes['autoresize']) || (!!$attributes['autoresize'] === true);
        $useNewLibrary = isset($attributes['use_new_library']) ? (!!$attributes['use_new_library']) : false;
        $redirect = isset($attributes['redirect']) ? $attributes['redirect'] : null;

        if ($useNewLibrary) {
            return self::generateBookingForm($subdomain, $arrangementID, $redirect);
        }
        return self::generateIframe($subdomain, $arrangementID, $enableResize);
    }


    private static function generateBookingForm($subdomain, $arrangementID, $redirectUrl)
    {
        global $recrasPlugin;

        $generatedDivID = uniqid('V');

        $packageText = '';
        if ($arrangementID) {
            $packageText = "package_id: " . $arrangementID . ",";
        }

        $redirect = '';
        if ($redirectUrl) {
            $redirect = "redirect_url: '" . $redirectUrl . "',";
        }

        return "
<div id='" . $generatedDivID . "'></div>
<script>
if (!self.fetch) {
    var scriptEl = document.createElement('script');
    scriptEl.src = 'https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.4/fetch.min.js';
    document.head.appendChild(scriptEl);
}
</script>
<script src='" . $recrasPlugin->baseUrl . '/js/onlinebooking.js?v=0.6.0' . "'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var bookingOptions = new RecrasOptions({
            recras_hostname: '" . $subdomain . ".recras.nl',
            element: document.getElementById('" . $generatedDivID . "'),
            locale: '" . Settings::externalLocale() . "',
            " . $packageText . "
            " . $redirect . "
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
