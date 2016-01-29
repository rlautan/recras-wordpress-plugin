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
            return __('Error: you have not set your Recras name yet', Plugin::TEXT_DOMAIN);
        }

        $arrangementID = isset($attributes['id']) ? $attributes['id'] : null;

        return self::generateIframe($subdomain, $arrangementID);
    }


    private static function generateIframe($subdomain, $arrangementID)
    {
        $url = 'https://' . $subdomain . '.recras.nl/onlineboeking';
        if ($arrangementID) {
            $url .= '/step1/arrangement/' . $arrangementID;
        }

        $html  = '<script>function resizeBookingIframe(obj){ obj.style.height=obj.contentWindow.document.body.scrollHeight+"px" }</script>';
        $html .= '<iframe src="' . $url . '" style="width:100%;height:450px" frameborder=0 scrolling="auto" onload="resizeBookingIframe(this)"></iframe>';
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
