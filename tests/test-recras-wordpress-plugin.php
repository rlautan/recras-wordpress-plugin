<?php

class RecrasPluginTest extends WP_UnitTestCase
{
	function testArrangementShortcode()
	{
		$post = $this->factory->post->create_and_get([
			'post_content' => 'Text with [arrangement] shortcode'
		]);
        $this->assertTrue(is_object($post), 'Creating a post should not fail');

        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<p>Text with ARRANGEMENT shortcode</p>' . "\n", $content);
	}
}
