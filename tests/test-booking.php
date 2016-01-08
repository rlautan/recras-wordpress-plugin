<?php
namespace Recras;

class OnlineBookingTest extends \WP_UnitTestCase
{
	function testShortcodeWithoutID()
	{
		$post = $this->factory->post->create_and_get([
			'post_content' => '[recras-booking]'
		]);
        $this->assertTrue(is_object($post), 'Creating a post should not fail');

        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, '<iframe'), 'Booking without ID should work');
	}

	function testInvalidIDinShortcode()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-booking id=foobar]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: ID is not a number' . "\n", $content, 'Non-numeric ID should fail');
	}

    function testRegularBooking()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-booking id=3]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, '<iframe'), 'Regular booking should include an iframe');
    }
}
