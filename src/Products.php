<?php
namespace Recras;

class Products
{
    /**
     * Add the [recras-product] shortcode
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function addProductShortcode($attributes)
    {
        if (!isset($attributes['id'])) {
            return __('Error: no ID set', Plugin::TEXT_DOMAIN);
        }
        if (!ctype_digit($attributes['id'])) {
            return __('Error: ID is not a number', Plugin::TEXT_DOMAIN);
        }
        if (!isset($attributes['show'])) {
            return __('Error: "show" option not set', Plugin::TEXT_DOMAIN);
        }
        if (!in_array($attributes['show'], self::getValidOptions())) {
            return __('Error: invalid "show" option', Plugin::TEXT_DOMAIN);
        }

        $subdomain = Settings::getSubdomain($attributes);
        if (!$subdomain) {
            return Plugin::getNoSubdomainError();
        }

        $products = self::getProducts($subdomain);
        if (!isset($products[$attributes['id']])) {
            return __('Error: product does not exist', Plugin::TEXT_DOMAIN);
        }
        $product = $products[$attributes['id']];

        switch ($attributes['show']) {
            case 'description':
                return '<span class="recras-description">' . $product->beschrijving . '</span>';
            case 'description_long':
                if ($product->uitgebreide_omschrijving) {
                    return '<span class="recras-description">' . $product->uitgebreide_omschrijving . '</span>';
                } else {
                    return '';
                }
            case 'duration':
                if ($product->duur) {
                    return '<span class="recras-duration">' . $product->duur . '</span>';
                } else {
                    return '';
                }
            case 'image_url':
                return $product->image_url;
            case 'minimum_amount':
                return '<span class="recras-amount">' . $product->minimum_aantal . '</span>';
            case 'price_excl_vat':
                return Price::format($product->prijs_exc);
            case 'price_incl_vat':
                return Price::format($product->prijs_inc);
            case 'title':
                return '<span class="recras-title">' . $product->weergavenaam . '</span>';
            default:
                return __('Error: unknown option', Plugin::TEXT_DOMAIN);
        }
    }


    /**
     * Clear product cache (transients)
     */
    public static function clearCache()
    {
        $subdomain = get_option('recras_subdomain');
        $error = Plugin::deleteTransient('recras_' . $subdomain . '_products');

        header('Location: ' . admin_url('admin.php?page=recras-clear-cache&msg=' . Plugin::getStatusMessage($error)));
        exit;
    }


    /**
     * Get products from the Recras API
     *
     * @param string $subdomain
     *
     * @return array|string
     */
    public static function getProducts($subdomain)
    {
        $json = get_transient('recras_' . $subdomain . '_products');
        if ($json === false) {
            try {
                $json = Http::get($subdomain, 'producten', 'api/json');
            } catch (\Exception $e) {
                return $e->getMessage();
            }

            set_transient('recras_' . $subdomain . '_products', $json, 86400);
        }
        if (!isset($json->results)) {
            return __('Error: external data does not contain any results', Plugin::TEXT_DOMAIN);
        }

        $products = [];
        foreach ($json->results as $product) {
            $products[$product->id] = $product;
        }
        // Sort alphabetically
        uksort($products, function($a, $b) use ($products) {
            return strcmp($products[$a]->weergavenaam, $products[$b]->weergavenaam);
        });
        return $products;
    }


    /**
     * Get all valid options for the "show" argument
     *
     * @return array
     */
    public static function getValidOptions()
    {
        return ['description', 'description_long', 'duration', 'image_url', 'minimum_amount', 'price_excl_vat', 'price_incl_vat', 'title'];
    }


    /**
     * Show the TinyMCE shortcode generator product form
     */
    public static function showForm()
    {
        require_once(dirname(__FILE__) . '/../editor/form-product.php');
    }

}
