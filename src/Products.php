<?php
namespace Recras;

class Products
{
    /**
     * Get productsfrom the Recras API
     *
     * @param string $subdomain
     *
     * @return array|string
     */
    public function getProducts($subdomain)
    {
        $baseUrl = 'https://' . $subdomain . '.recras.nl/api/json/producten';
        $json = @file_get_contents($baseUrl);
        if ($json === false) {
            return __('Error: could not retrieve external data', Plugin::TEXT_DOMAIN);
        }
        $json = json_decode($json);
        if (is_null($json)) {
            return __('Error: could not parse external data', Plugin::TEXT_DOMAIN);
        }
        if (!isset($json->results)) {
            return __('Error: external data does not contain any results', Plugin::TEXT_DOMAIN);
        }

        $products = [];
        foreach ($json->results as $product) {
            $products[$product->id] = $product;
        }
        return $products;
    }

}
