<?php
namespace Recras;

class Http
{
    /**
     * @param string $subdomain
     * @param string $uri
     * @param string $api
     *
     * @return array|object|string
     *
     * @throws Exception\JsonParseException
     * @throws Exception\UrlException
     */
    public static function get($subdomain, $uri, $api = 'api2.php')
    {
        $json = @file_get_contents('https://' . $subdomain . '.recras.nl/' . $api . '/' . $uri);
        if ($json === false) {
            throw new Exception\UrlException(__('Error: could not retrieve external data', Plugin::TEXT_DOMAIN));
        }
        $json = json_decode($json);
        if (is_null($json)) {
            throw new Exception\JsonParseException(__('Error: could not parse external data', Plugin::TEXT_DOMAIN));
        }
        return $json;
    }
}
