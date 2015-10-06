<?php
namespace Recras;

class PluginTest extends \WP_UnitTestCase
{
    function __construct()
    {
        update_option('recras_currency', '€');
        update_option('recras_subdomain', 'demo');
    }

	function testShortcodeWithoutID()
	{
		$post = $this->factory->post->create_and_get([
			'post_content' => '[arrangement]'
		]);
        $this->assertTrue(is_object($post), 'Creating a post should not fail');

        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: no ID set' . "\n", $content, 'Not setting ID should fail');
	}

	function testInvalidIDinShortcode()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=foobar]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: ID is not a number' . "\n", $content, 'Non-numeric ID should fail');
	}

	function testShortcodeWithValidIDWithoutShow()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: "show" option not set' . "\n", $content, 'Not setting "show" option should fail');
	}

	function testShortcodeWithInvalidShow()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=invalid]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: invalid "show" option' . "\n", $content, '...');
	}

	function testShortcodeShowTitle()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=title]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras_title">2 daags vergader arrangement</span>' . "\n", $content, 'Should show title');
	}

	function testShortcodeShowPersons()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=persons]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras_persons">10</span>' . "\n", $content, 'Should show number of persons');
	}

	function testShortcodeShowPrices()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=price_total_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras_price">€ 376.886792453</span>' . "\n", $content, 'Should show total price excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=price_total_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras_price">€ 399.5</span>' . "\n", $content, 'Should show total price incl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=price_pp_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras_price">€ 37.6886792453</span>' . "\n", $content, 'Should show price per person excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=price_pp_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras_price">€ 39.95</span>' . "\n", $content, 'Should show price per person incl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=programme]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, '<table'), 'Should return an HTML table');
	}


    function testTooLongSubdomain()
    {
        $plugin = new Plugin;
        $result = $plugin->sanitizeSubdomain('ThisSubdomainIsLongerThanAllowedButDoesNotContainAnyInvalidCharacters');
        $this->assertFalse($result, 'Too long subdomain should be invalid');
    }

    function testInvalidSubdomain()
    {
        $plugin = new Plugin;
        $result = $plugin->sanitizeSubdomain('foo@bar');
        $this->assertFalse($result, 'Subdomain with invalid characters should be invalid');
    }

    function testValidSubdomain()
    {
        $plugin = new Plugin;
        $result = $plugin->sanitizeSubdomain('demo');
        $this->assertEquals('demo', $result, 'Valid subdomain should be valid');
    }
}
