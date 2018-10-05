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
        $redirect = isset($attributes['redirect']) ? $attributes['redirect'] : null;

        return self::generateBookingForm($subdomain, $arrangementID, $redirect);
    }


    private static function generateBookingForm($subdomain, $arrangementID, $redirectUrl)
    {
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

    /**
     * Show the TinyMCE shortcode generator contact form
     */
    public static function showForm()
    {
        require_once(dirname(__FILE__) . '/../editor/form-booking.php');
    }
}
