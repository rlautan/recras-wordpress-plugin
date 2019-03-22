<?php
namespace Recras;

class ProductsTest extends WordPressUnitTestCase
{
    function testShortcodeWithoutID()
    {
        $content = $this->createPostAndGetContent('[recras-product]');
        $this->assertEquals('Error: no ID set' . "\n", $content, 'Not setting ID should fail');
    }

    function testInvalidIDinShortcode()
    {
        $content = $this->createPostAndGetContent('[recras-product id=foobar]');
        $this->assertEquals('Error: ID is not a number' . "\n", $content, 'Non-numeric ID should fail');
    }

    function testShortcodeWithValidIDWithoutShow()
    {
        $content = $this->createPostAndGetContent('[recras-product id=42]');
        $this->assertEquals('<span class="recras-title">2 uur klimmen</span>' . "\n", $content, 'Not setting "show" option should default to title');
    }

    function testShortcodeWithInvalidShow()
    {
        $content = $this->createPostAndGetContent('[recras-product id=42 show=invalid]');
        $this->assertEquals('<span class="recras-title">2 uur klimmen</span>' . "\n", $content, 'Invalid "show" option should default to title');
    }

    function testGetProducts()
    {
        $plugin = new Products;
        $products = $plugin::getProducts('demo');
        $this->assertGreaterThan(0, count($products), 'getProducts should return a non-empty array');
    }

    function testShortcodeShowTitle()
    {
        $content = $this->createPostAndGetContent('[recras-product id=42 show=title]');
        $this->assertEquals('<span class="recras-title">2 uur klimmen</span>' . "\n", $content, 'Should show title');
    }

    function testShortcodeShowPrices()
    {
        $content = $this->createPostAndGetContent('[recras-product id=42 show=price_incl_vat]');
        $this->assertEquals('<span class="recras-price">€ 17.50</span>' . "\n", $content, 'Should show price incl. vat');
    }

    function testShortcodeShowDescription()
    {
        $content = $this->createPostAndGetContent('[recras-product id=42 show=description]');
        $this->assertEquals('<span class="recras-description">Twee uur klimmen in ons bos</span>' . "\n", $content, 'Should show description');
    }

    function testShortcodeShowLongDescription()
    {
        $content = $this->createPostAndGetContent('[recras-product id=42 show=description_long]');
        $this->assertNotFalse(strpos($content, '<span class="recras-description">Twee uur klimmen in ons klimbos, met de langste zipline van Nederland</span>'), 'Should show long description');
    }

    function testShortcodeShowEmptyLongDescription()
    {
        $content = $this->createPostAndGetContent('[recras-product id=17 show=description_long]');
        $this->assertEquals('' . "\n", $content, 'Should not show empty description');
    }

    function testShortcodeShowDuration()
    {
        $content = $this->createPostAndGetContent('[recras-product id=42 show=duration]');
        $this->assertEquals('<span class="recras-duration">2:00</span>' . "\n", $content, 'Should show duration');
    }

    function testShortcodeShowEmptyDuration()
    {
        $content = $this->createPostAndGetContent('[recras-product id=17 show=duration]');
        $this->assertEquals('' . "\n", $content, 'Should not show duration');
    }

    function testShortcodeShowMinimumAmount()
    {
        $content = $this->createPostAndGetContent('[recras-product id=42 show=minimum_amount]');
        $this->assertEquals('<span class="recras-amount">1</span>' . "\n", $content, 'Should show minimum amount');
    }
}
