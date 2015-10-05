<?php

class RecrasPluginTest extends WP_UnitTestCase
{
	function testArrangementShortcode()
	{
		$post = $this->factory->post->create_and_get([
			'post_content' => '[arrangement]'
		]);
        $this->assertTrue(is_object($post), 'Creating a post should not fail');

        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: no ID set' . "\n", $content, 'Not setting ID should fail');
	}
}
