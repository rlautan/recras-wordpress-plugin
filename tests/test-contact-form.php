<?php
namespace Recras;

class ContactFormTest extends WordPressUnitTestCase
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

    function testRegularContactForm()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=3]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, '<h2'), 'Regular contact form should include a title');
        $this->assertNotFalse(strpos($content, '<dl'), 'Regular contact form should be a definition list');
        $this->assertNotFalse(strpos($content, '<dt'), 'Regular contact form should include definition terms');
        $this->assertNotFalse(strpos($content, '<dd'), 'Regular contact form should include definitions');
        $this->assertNotFalse(strpos($content, '<label'), 'Regular contact form should show labels');
        $this->assertNotFalse(strpos($content, 'placeholder='), 'Regular contact form should show placeholders');
    }

    function testContactformNoTitle()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=3 showtitle=false]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertFalse(strpos($content, '<h2'), 'Setting showtitle to false should not generate a title');
    }

    function testContactformNoLabels()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=3 showlabels="no"]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertFalse(strpos($content, '<label'), 'Setting showlabels to false should not generate labels');
    }

    function testContactformNoPlaceholders()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=3 showplaceholders=0]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertFalse(strpos($content, 'placeholder='), 'Setting showplaceholders to false should not generate placeholders');
    }

    function testContactformAsOrderedList()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=3 element=ol]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertFalse(strpos($content, '<dl'), 'Contact form with element set to ol should not be a definition list');
        $this->assertNotFalse(strpos($content, '<ol'), 'Contact form with element set to ol should be an ordered list');
        $this->assertNotFalse(strpos($content, '<li'), 'Contact form with element set to ol should have list items');
    }

    function testContactformAsTable()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=3 element=table]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertFalse(strpos($content, '<dl'), 'Contact form with element set to ol should not be a definition list');
        $this->assertNotFalse(strpos($content, '<table'), 'Contact form with element set to table should be a table');
        $this->assertNotFalse(strpos($content, '<tr'), 'Contact form with element set to table should have at least one row');
        $this->assertNotFalse(strpos($content, '<td'), 'Contact form with element set to table should have at least one cell');
    }

    function testSubmitDifferentText()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-contact id=3 submittext="Ni"]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, '<input type="submit" value="Ni">'), 'Changing submit button text should work');
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
