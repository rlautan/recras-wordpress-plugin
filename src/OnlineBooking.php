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
        if (!isset($attributes['id'])) {
            return __('Error: no ID set', Plugin::TEXT_DOMAIN);
        }
        if (!ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }

        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return __('Error: you have not set your Recras name yet', Plugin::TEXT_DOMAIN);
        }

        return self::generateIframe($subdomain, $attributes['id']);
    }


    private static function generateIframe($subdomain, $formID)
    {
        $html  = '<script>function resizeBookingIframe(obj){ obj.style.height=obj.contentWindow.document.body.scrollHeight+"px" }</script>';
        $html .= '<iframe src="https://' . $subdomain . '.recras.nl/contactformulier/index/id/' . $formID . '" style="width:100%;height:400px" frameborder=0 scrolling="auto" seamless onload="resizeBookingIframe(this)"></iframe>';
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
