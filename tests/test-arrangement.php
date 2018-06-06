<?php
namespace Recras;

class ArrangementTest extends \WP_UnitTestCase
{
	function testShortcodeWithoutID()
	{
		$post = $this->factory->post->create_and_get([
			'post_content' => '[recras-package]'
		]);
        $this->assertTrue(is_object($post), 'Creating a post should not fail');

        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: no ID set' . "\n", $content, 'Not setting ID should fail');
	}

	function testInvalidIDinShortcode()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=foobar]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: ID is not a number' . "\n", $content, 'Non-numeric ID should fail');
	}

	function testShortcodeWithValidIDWithoutShow()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: "show" option not set' . "\n", $content, 'Not setting "show" option should fail');
	}

	function testShortcodeWithInvalidShow()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=invalid]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('Error: invalid "show" option' . "\n", $content, '...');
	}

	function testShortcodeShowTitle()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=title]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-title">Actieve mysqlFamiliedag</span>' . "\n", $content, 'Should show title');
	}

	function testShortcodeDescription()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=description]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertTrue(strpos($content, '<p><strong>Ontvangst:') !== false, 'Should show description');
	}

	function testShortcodeDuration()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=duration]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-duration">6:15</span>' . "\n", $content, 'Should show duration');
	}

    function testShortcodeImage()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=image_url]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('/api2.php/arrangementen/7/afbeelding' . "\n", $content, 'Should return image URL');
    }

    function testShortcodeImageInTag()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '<img src="[recras-package id=7 show=image_url]">'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<p><img src="/api2.php/arrangementen/7/afbeelding"></p>' . "\n", $content, 'Should return image URL');
    }

	function testShortcodeLocation()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=6 show=location]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-location">No location specified</span>' . "\n", $content, 'Should show location');
	}

	function testShortcodeShowPersons()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=persons]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-persons">10</span>' . "\n", $content, 'Should show number of persons');
	}

	function testShortcodeShowPrices()
	{
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=price_total_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 336.13</span>' . "\n", $content, 'Should show total price excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=price_total_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 362.50</span>' . "\n", $content, 'Should show total price incl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=price_pp_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 33.61</span>' . "\n", $content, 'Should show price per person excl. vat');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=price_pp_incl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 36.25</span>' . "\n", $content, 'Should show price per person incl. vat');
	}

    function testSingleDayProgramme()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=5 show=programme]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertNotFalse(strpos($content, '<table'), 'Should return an HTML table');
        $this->assertNotFalse(strpos($content, '<thead'), 'Should contain a table header');
        $this->assertEquals(0, substr_count($content, '<tr class="recras-new-day'), 'Should stay on one day');
    }

    function testProgrammeWithTimeOffset()
    {
        $this->markTestSkipped('TODO: implement proper time offset'); //TODO

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 starttime="22:00" show=programme]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals(2, substr_count($content, '<tr class="recras-new-day'), 'Should span two days');
    }

    function testMultiDayProgramme()
    {
        $this->markTestSkipped('TODO: there is no multi-day programme anymore'); //TODO

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=programme]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals(2, substr_count($content, '<tr class="recras-new-day'), 'Should span two days');
    }

    function testShortcodeProgrammeWithoutHeader()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=programme showheader=false]'
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
        $packages = $plugin->getArrangements('demo', true);
        $packagesOnline = array_filter($packages, function($p) {
            return $p->mag_online;
        });
        $this->assertEquals($packages, $packagesOnline, 'All packages should be bookable online');
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
            'post_content' => '[recras-package id=7 show=price_total_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">€ 336,13</span>' . "\n", $content, 'Should respect decimal setting');
        update_option('recras_decimal', '.');
    }

    function testChangeCurrency()
    {
        update_option('recras_currency', '¥');
        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-package id=7 show=price_total_excl_vat]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-price">¥ 336.13</span>' . "\n", $content, 'Should respect currency setting');
        update_option('recras_currency', '€');
    }

    function testOldShortcodes()
    {
        $post = $this->factory->post->create_and_get([
            'post_content' => '[arrangement id=7 show=title]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-title">Actieve Familiedag</span>' . "\n", $content, 'Old shortcode (< 1.0.0) should still work');

        $post = $this->factory->post->create_and_get([
            'post_content' => '[recras-arrangement id=7 show=title]'
        ]);
        $content = apply_filters('the_content', $post->post_content);
        $this->assertEquals('<span class="recras-title">Actieve Familiedag</span>' . "\n", $content, 'Old shortcode (< 1.11.0) should still work');
    }
}
