<?php

namespace XLiteWeb\tests;

/**
 * @author cerber
 */
class testFeaturedProducts extends \XLiteWeb\AXLiteWeb
{

    protected $dataCategory = ['nameCategory' => 'iGoods - Product Filter',
                               'categoryId' => '2',
        'products' =>  [['productId' => '37','productName' =>'Apple iPhone 6S [Options & Attributes] [Custom tabs]'],
                        ['productId' => '41','productName' =>'Apple iPhone 6S Plus [Options & Attributes]'],
                        ['productId' => '42','productName' =>'Apple iPhone SE [Options & Attributes] [Tabs]']
        ]
    ];

    protected $dataProduct = [
        'products' => [['productId' => '39','productName' =>'Apple Watch Sport 42mm with Sport Band'],
                       ['productId' => '40','productName' =>'Astro A30 System']
        ]
    ];

    public function testFeaturedProductsTest()
    {
//------------------Product------------------------------------------------------------------------------

        // Add new featured product
        $featuredProducts = $this->openAdminFeaturedProducts();

        $featuredProducts->buttonAddFeaturedProducts();

        $featuredProducts->componentAddFeaturedProducts->choiceProduct($this->dataProduct);
        $this->assertTrue($featuredProducts->checkArrayNameProduct($this->dataProduct),"name product not found on Featured Products Page");

        //check Products on Home Page
        $storeFront = $this->openHomePage();
        $this->assertTrue($storeFront->checkArrayNameProductOnIndexPage($this->dataProduct),"name product not found on Featured Products Page");

        //check sorting products on Featured Products
        $arrayDrop = $featuredProducts->dragDropFeaturedProducts($this->dataProduct['products'][0]['productName'], $this->dataProduct['products'][1]['productName']);

        //check Product on Home Page
        $storeFront = $this->openHomePage();
        $arrayDropStoreFront = $storeFront->dragDropArrayOnFeaturedProducts();
        $result = empty(array_diff_assoc($arrayDrop, $arrayDropStoreFront));
        $this->assertTrue($result,"product sorting is not successful on Featured Category");

        //delete Product on Featured Products Page
        $featuredProducts = $this->openAdminFeaturedProducts();
        $featuredProducts->deleteArrayProduct($this->dataProduct);
        $this->assertFalse($featuredProducts->checkArrayNameProduct($this->dataProduct),"name product not found on Featured Products Page");

        //check Products on Home Page
        $storeFront = $this->openHomePage();
        $this->assertFalse($storeFront->checkArrayNameProduct($this->dataProduct),"name product not found on Featured Products Page");

//------------------Product-Category---------------------------------------------------------------------

        // Add new featured product on Category Page
        $featuredProductsCategory = $this->openAdminFeaturedProductsCategory();
        $featuredProductsCategory->buttonAddFeaturedProducts();

        $featuredProductsCategory->componentAddFeaturedProducts->choiceProduct($this->dataCategory);
        $this->assertTrue($featuredProductsCategory->checkArrayNameProduct($this->dataCategory),"name product not found on Featured Products Page");

        //check Product on Category Page
        $customCategory = $this->openCustomerCategory();
        $this->assertTrue($customCategory->checkArrayNameProductOnIndexPage($this->dataCategory),"name product not found on Featured Category");

        $arrayDrop = $featuredProductsCategory->dragDropFeaturedProducts($this->dataCategory['products'][1]['productName'], $this->dataCategory['products'][2]['productName']);

        //check Product on Category Page
        $customCategory = $this->openCustomerCategory();
        $arrayDropCustomCategory = $customCategory->dragDropArrayOnFeaturedProducts();
        $result = empty(array_diff_assoc($arrayDrop, $arrayDropCustomCategory));
        $this->assertTrue($result,"product sorting is not successful on Featured Category");


        // check count on Categories Page
        $adminСategories = $this->openAdminCategories();
        $this->assertEquals($adminСategories->countFeaturedProduct($this->dataCategory['nameCategory']), count($this->dataCategory['products']), 'The product has Not been added to Cart .');

        //delete Product on Featured Products Page
        $featuredProductsCategory = $this->openAdminFeaturedProductsCategory();
        $featuredProductsCategory->deleteArrayProduct($this->dataCategory);
        $this->assertFalse($featuredProductsCategory->checkArrayNameProduct($this->dataCategory),"name product not found on Featured Products Page");

        //check Product on Category Page
        $customCategory = $this->openCustomerCategory();
        $this->assertFalse($customCategory->checkArrayNameProduct($this->dataCategory),"name product not found on Featured Category");

        // check count on Categories Page
        $adminСategories = $this->openAdminCategories();
        $this->assertEquals($adminСategories->countFeaturedProduct($this->dataCategory['nameCategory']), 0, 'The product has Not been added to Cart .');

    }

    public function openHomePage()
    {

        $storeFront = $this->CustomerIndex;
        $storeFront->load();
        $this->assertTrue($storeFront->validate(), 'Storefront is inaccessible.');

        return $storeFront;

    }

    public function openAdminFeaturedProducts()
    {

        $featuredProducts = $this->AdminFeaturedProducts;
        $this->assertTrue($featuredProducts->load(true), 'Error loading Featured Products page.');

        return $featuredProducts;

    }

    public function openAdminFeaturedProductsCategory()
    {

        $featuredProductsCategory = $this->AdminFeaturedProductsCategory;
        $this->assertTrue($featuredProductsCategory->load(true,$this->dataCategory['categoryId']), 'Error loading Featured Products page.');

        return $featuredProductsCategory;

    }

    public function openCustomerCategory()
    {

        $customCategory = $this->CustomerCategory;
        $this->assertTrue($customCategory->load(false,$this->dataCategory['categoryId']), 'Error loading Featured Products page.');
        $this->assertTrue($customCategory->validate(), 'Category is inaccessible.');

        return $customCategory;

    }

    public function openAdminCategories()
    {

        $adminСategories = $this->AdminCategories;
        $this->assertTrue($adminСategories->load(false), 'Error loading Featured Products page.');

        return $adminСategories;

    }

}
