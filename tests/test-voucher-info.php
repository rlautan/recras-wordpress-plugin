<?php
namespace Recras;

class VoucherInfoTest extends WordPressUnitTestCase
{
    function testShortcodeWithoutID()
    {
        $content = $this->createPostAndGetContent('[recras-voucher-info]');
        $this->assertEquals('Error: no ID set' . "\n", $content, 'Not setting ID should fail');
    }

    function testInvalidIDinShortcode()
    {
        $content = $this->createPostAndGetContent('[recras-voucher-info id=foobar]');
        $this->assertEquals('Error: ID is not a number' . "\n", $content, 'Non-numeric ID should fail');
    }

    function testShortcodeWithoutShow()
    {
        $content = $this->createPostAndGetContent('[recras-voucher-info id=1]');
        $this->assertEquals('<span class="recras-title">Kadobon voor 2 keer klimmen</span>' . "\n", $content, 'Not setting "show" option should default to name');
    }

    function testShortcodeWithInvalidShow()
    {
        $content = $this->createPostAndGetContent('[recras-voucher-info id=1 show=invalid]');
        $this->assertEquals('<span class="recras-title">Kadobon voor 2 keer klimmen</span>' . "\n", $content, 'Invalid "show" option should default to name');
    }

    function testShortcodeShowName()
    {
        $content = $this->createPostAndGetContent('[recras-voucher-info id=1 show=name]');
        $this->assertEquals('<span class="recras-title">Kadobon voor 2 keer klimmen</span>' . "\n", $content, 'Should show name');
    }

    function testShortcodeShowPrice()
    {
        $content = $this->createPostAndGetContent('[recras-voucher-info id=1 show=price]');
        $this->assertEquals('<span class="recras-price">€ 30.00</span>' . "\n", $content, 'Should show price');
    }

    function testShortcodeShowValidity()
    {
        $content = $this->createPostAndGetContent('[recras-voucher-info id=1 show=validity]');
        $this->assertEquals('365' . "\n", $content, 'Should show validity');
    }
}
