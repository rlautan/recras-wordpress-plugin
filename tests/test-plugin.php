<?php
namespace Recras;

class PluginTest extends \WP_UnitTestCase
{
    function __construct()
    {
        update_option('recras_currency', '€');
        update_option('recras_subdomain', 'demo');
    }

    function testTooLongSubdomain()
    {
        $plugin = new Settings;
        $result = $plugin->sanitizeSubdomain('ThisSubdomainIsLongerThanAllowedButDoesNotContainAnyInvalidCharacters');
        $this->assertFalse($result, 'Too long subdomain should be invalid');
    }

    function testInvalidSubdomain()
    {
        $plugin = new Settings;
        $result = $plugin->sanitizeSubdomain('foo@bar');
        $this->assertFalse($result, 'Subdomain with invalid characters should be invalid');
    }

    function testValidSubdomain()
    {
        $plugin = new Settings;
        $result = $plugin->sanitizeSubdomain('demo');
        $this->assertEquals('demo', $result, 'Valid subdomain should be valid');
    }

    function testGetArrangements()
    {
        $plugin = new Arrangement;
        $arrangements = $plugin->getArrangements('demo');
        $this->assertGreaterThan(0, count($arrangements), 'getArrangements should return a non-empty array');
    }

    function testGetArrangementsInvalidDomain()
    {
        $plugin = new Arrangement;
        $arrangements = $plugin->getArrangements('ObviouslyFakeSubdomainThatDoesNotExist');
        $this->assertTrue(is_string($arrangements), 'getArrangements on a non-existing subdomain should return an error message');
    }

}
