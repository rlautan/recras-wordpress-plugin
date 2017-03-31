<?php
namespace Recras;

class ArrangementTest extends \WP_UnitTestCase
{
	function testShortcodeWithoutID()
	{
		$post = $this->factory->post->create_and_get([
			'post_content' => '[recras-arrangement]'
		]);
        $this->assertTrue(is_object($post), 'Creating a post should not fail');

        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: no ID set' . "\n", $content, 'Not setting ID should fail');
	}

	function testInvalidIDinShortcode()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=foobar]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: ID is not a number' . "\n", $content, 'Non-numeric ID should fail');
	}

	function testShortcodeWithValidIDWithoutShow()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: "show" option not set' . "\n", $content, 'Not setting "show" option should fail');
	}

	function testShortcodeWithInvalidShow()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=invalid]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: invalid "show" option' . "\n", $content, '...');
	}

	function testShortcodeShowTitle()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=title]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-title">2-daags vergaderarrangement</span>' . "\n", $content, 'Should show title');
	}

	function testShortcodeDescription()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=7 show=description]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertTrue(strpos($content, '<p><strong>Ontvangst:') !== false, 'Should show description');
	}

	function testShortcodeDuration()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=duration]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-duration">1:7:30</span>' . "\n", $content, 'Should show duration');
	}

    function testShortcodeImage()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=image_url]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('/api2.php/arrangementen/7/afbeelding', $content, 'Should return image URL');
    }

	function testShortcodeLocation()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=location]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-location">No location specified</span>' . "\n", $content, 'Should show location');
	}

	function testShortcodeShowPersons()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=persons]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-persons">10</span>' . "\n", $content, 'Should show number of persons');
	}

	function testShortcodeShowPrices()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=price_total_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 409.91</span>' . "\n", $content, 'Should show total price excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=price_total_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 434.50</span>' . "\n", $content, 'Should show total price incl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=price_pp_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 40.99</span>' . "\n", $content, 'Should show price per person excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=price_pp_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 43.45</span>' . "\n", $content, 'Should show price per person incl. vat');
	}

    function testSingleDayProgramme()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=5 show=programme]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, '<table'), 'Should return an HTML table');
        $this->assertNotFalse(strpos($content, '<thead'), 'Should contain a table header');
        $this->assertEquals(0, substr_count($content, '<tr class="recras-new-day'), 'Should stay on one day');
    }

    function testProgrammeWithTimeOffset()
    {
        $this->markTestSkipped('TODO: implement proper time offset');
        /*$post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=5 starttime="16:00" show=programme]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals(2, substr_count($content, '<tr class="recras-new-day'), 'Should span two days');*/
    }

    function testMultiDayProgramme()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=programme]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals(2, substr_count($content, '<tr class="recras-new-day'), 'Should span two days');
    }

    function testShortcodeProgrammeWithoutHeader()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=programme showheader=false]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, '<table'), 'Should return an HTML table');
        $this->assertFalse(strpos($content, '<thead'), 'Should not contain a table header');
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
        $this->assertTrue(isset($arrangements[8]), 'Should include arrangements that are not bookable online');
        $this->assertTrue(isset($arrangements[18]), 'Should include arrangements that are bookable online');
    }

    function testGetOnlineArrangements()
    {
        $plugin = new Arrangement;
        $arrangements = $plugin->getArrangements('demo', true);
        $this->assertFalse(isset($arrangements[8]), 'Should not include arrangements that are not bookable online');
        $this->assertTrue(isset($arrangements[18]), 'Should include arrangements that are bookable online');
    }

    function testGetFormArrangementsInvalidForm()
    {
        $plugin = new Arrangement;
        $arrangements = $plugin->getArrangementsForContactForm('demo', 1337);
        $this->assertEquals(0, count($arrangements), 'Non-existing contact form should return an empty array');
    }

    function testGetFormArrangements()
    {
        $plugin = new Arrangement;
        $arrangements = $plugin->getArrangementsForContactForm('demo', 1);
        $this->assertGreaterThan(0, count($arrangements), 'Existing contact form should return a non-empty array');
    }

    function testChangeDecimal()
    {
        update_option('recras_decimal', ',');
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=price_total_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 409,91</span>' . "\n", $content, 'Should respect decimal setting');
        update_option('recras_decimal', '.');
    }

    function testChangeCurrency()
    {
        update_option('recras_currency', '¥');
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=8 show=price_total_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">¥ 409.91</span>' . "\n", $content, 'Should respect currency setting');
        update_option('recras_currency', '€');
    }
}
