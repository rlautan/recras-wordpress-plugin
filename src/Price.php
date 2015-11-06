<?php
namespace Recras;

class Price
{
    /**
     * Format a price
     *
     * @param float $price
     *
     * @return string
     */
    public static function format($price)
    {
        $currency = get_option('recras_currency');
        $decimalSeparator = get_option('recras_decimal');
        if ($decimalSeparator === false) {
            $decimalSeparator = '.';
        }
        return '<span class="recras-price">' . $currency . ' ' . number_format($price, 2, $decimalSeparator, '') . '</span>';
    }
}
