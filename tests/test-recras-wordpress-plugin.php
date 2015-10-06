<?php
namespace Recras;

class PluginTest extends \WP_UnitTestCase
{
    function __construct()
    {
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
        $this->assertEquals('2 daags vergader arrangement' . "\n", $content, 'Should show title');
	}

	function testShortcodeShowPersons()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=persons]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('10' . "\n", $content, 'Should show number of persons');
	}

	function testShortcodeShowPrices()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=price_total_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('376.886792453' . "\n", $content, 'Should show total price excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=price_total_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('399.5' . "\n", $content, 'Should show total price incl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=price_pp_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('37.6886792453' . "\n", $content, 'Should show price per person excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8 show=price_pp_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('39.95' . "\n", $content, 'Should show price per person  incl. vat');
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
