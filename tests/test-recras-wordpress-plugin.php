<?php
namespace Recras;

class PluginTest extends \WP_UnitTestCase
{
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

	function testValidShortcode()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=8]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('ARRANGEMENT' . "\n", $content, '...');
	}
}
