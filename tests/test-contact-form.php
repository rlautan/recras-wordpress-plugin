<?php
namespace Recras;

class ContactFormTest extends \WP_UnitTestCase
{
	function testShortcodeWithoutID()
	{
		$post = $this->factory->post->create_and_get([
			'post_content' => '[recras-contact]'
		]);
        $this->assertTrue(is_object($post), 'Creating a post should not fail');

        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: no ID set' . "\n", $content, 'Not setting ID should fail');
	}

	function testInvalidIDinShortcode()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=foobar]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: ID is not a number' . "\n", $content, 'Non-numeric ID should fail');
	}

    function testContactformNoTitle()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=3 showtitle=false]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertFalse(strpos($content, '<h3'), 'Setting showtitle to false should not generate a title');
    }

    function testGetForms()
    {
        $plugin = new ContactForm;
        $forms = $plugin->getForms('demo');
        $this->assertGreaterThan(0, count($forms), 'getForms should return a non-empty array');
    }

    function testGetFormsInvalidDomain()
    {
        $plugin = new ContactForm;
        $forms = $plugin->getForms('ObviouslyFakeSubdomainThatDoesNotExist');
        $this->assertTrue(is_string($forms), 'getForms on a non-existing subdomain should return an error message');
    }
}
