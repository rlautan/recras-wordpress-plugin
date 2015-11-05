<?php
namespace Recras;

class ProductsTest extends \WP_UnitTestCase
{
    function testGetProducts()
    {
        $plugin = new Products;
        $products = $plugin->getProducts('demo');
        $this->assertGreaterThan(0, count($products), 'getProducts should return a non-empty array');
    }

    function testGetProductsInvalidDomain()
    {
        $plugin = new Products;
        $products = $plugin->getProducts('ObviouslyFakeSubdomainThatDoesNotExist');
        $this->assertTrue(is_string($products), 'getProducts on a non-existing subdomain should return an error message');
    }
}
