<?php
namespace Recras;

class Http
{
    private static function jsonErrorMessage($errorCode) {
        switch ($errorCode) {
            case \JSON_ERROR_DEPTH:
                return __('The maximum stack depth has been exceeded', Plugin::TEXT_DOMAIN);
            case \JSON_ERROR_STATE_MISMATCH:
                return __('Invalid or malformed JSON', Plugin::TEXT_DOMAIN);
            case \JSON_ERROR_CTRL_CHAR:
                return __('Control character error, possibly incorrectly encoded', Plugin::TEXT_DOMAIN);
            case \JSON_ERROR_SYNTAX:
                return __('Syntax error', Plugin::TEXT_DOMAIN);
            case \JSON_ERROR_UTF8:
                return __('Malformed UTF-8 characters, possibly incorrectly encoded', Plugin::TEXT_DOMAIN);
            default:
                return __('Unknown JSON error', Plugin::TEXT_DOMAIN);
        }
    }


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
        $ch = curl_init('https://' . $subdomain . '.recras.nl/' . $api . '/' . $uri);
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($ch);

        if ($json === false) {
            $errorMsg = curl_error($ch);
            /*$statusCode = curl_getinfo($ch, \CURLINFO_HTTP_CODE);
            if ($statusCode != 200) {
                $errorMsg .= ' (HTTP ' . $statusCode . ')';
            }*/
            throw new Exception\UrlException(sprintf(__('Error: could not retrieve data from Recras. The error message received was: %s', Plugin::TEXT_DOMAIN), $errorMsg));
        }
        $json = json_decode($json);
        if (is_null($json)) {
            if (function_exists('json_last_error_msg')) {
                $errorMsg = json_last_error_msg();
            } else {
                $errorMsg = self::jsonErrorMessage(json_last_error());
            }
            throw new Exception\JsonParseException(sprintf(__('Error: could not parse data from Recras. The error message was: %s', Plugin::TEXT_DOMAIN), $errorMsg));
        }

        curl_close($ch);
        return $json;
    }
}
