<?php
// vim: set ts=4 sw=4 sts=4 et:

namespace XLiteWeb;
use XLiteTest\Framework\Config;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Interactions\WebDriverActions;



trait FeaturedProductsTrait
{
    /**
     * @findBy 'cssSelector'
     * @var WebDriverBy
     * @property RemoteWebElement $get_buttonAddFeaturedProducts
     */
    protected $buttonAddFeaturedProducts = ".btn.regular-button.popup-product-selection";

    public function buttonAddFeaturedProducts() //buttonAddFeaturedProductsTrait()
    {

        $this->driver->findElement($this->buttonAddFeaturedProducts)->click();

    }

    public function checkArrayNameProduct($data)
    {

        $this->waitForAjax();

        $result = true;

        foreach ($data['products'] as $product) {

            if ($this->checkNameProduct($product['productName']) === false ){

                $result = false;
                break;

            }

        }

        return $result;

    }

    public function checkNameProduct($name)
    {

        $this->waitForAjax();

        return $this->isElementPresent(WebDriverBy::xpath('.//*[a[@title="' .$name . '"]]'));

    }

    public function deleteArrayProduct($data)
    {

        $this->waitForAjax();

        $result = true;

        foreach ($data['products'] as $product) {

            if ($this->deleteProductOnFeaturedProductsPage($product['productName']) === false ){

                $result = false;
                break;

            }

        }

        return $result;

    }

    public function deleteProductOnFeaturedProductsPage($name)
    {

        $this->waitForAjax();

        $buttonDeleteFeaturedProductsLocator = './/*[a[@title="' .$name . '"]]/following::*[15]';
        $this->driver->findElement(WebDriverBy::xpath($buttonDeleteFeaturedProductsLocator))->click();
        $this->saveChanges();

    }

    public function checkArrayNameProductOnIndexPage($data)
    {

        $this->waitForAjax();

        $result = true;

        foreach ($data['products'] as $product) {

            if ($this->checkNameProductOnIndexPage($product['productName']) === false ){

                $result = false;
                break;

            }

        }

        return $result;

    }

    public function checkNameProductOnIndexPage($name)
    {

        $this->waitForAjax();

        $locator = './/*[@class=' . '"block block-block block-featured-products"' . ']//a[text()="' . $name . '"]';
        return $this->isElementPresent(WebDriverBy::xpath($locator));

    }

    public function dragDropFeaturedProducts($draggableNameProduct,$targetNameProduct)
    {

        $this->waitForAjax();

        $arrayDrop = array();

        //find drag element
        $locatorElement = './/*[a[@title="' . $draggableNameProduct . '"]]/ancestor::*[2]';

        $attributeClass = $this->driver->findElement(WebDriverBy::xpath($locatorElement))->getAttribute("class");
        $locatorDragElement =  '.' . str_replace(' ', '.', $attributeClass) . ' .move';

        //find drop element
        $locatorDropElement = './/*[a[@title="' . $targetNameProduct . '"]]/ancestor::*[2]';

        $attributeClass = $this->driver->findElement(WebDriverBy::xpath($locatorDropElement))->getAttribute("class");
        $locatorTargetElement =  '.' . str_replace(' ', '.', $attributeClass) . ' .move';

        $draggable = $this->driver->findElement(WebDriverBy::cssSelector($locatorDragElement));
        $target    = $this->driver->findElement(WebDriverBy::cssSelector($locatorTargetElement));

        //dragAndDrop
        $dragDropElement = new WebDriverActions($this->driver);
        $dragDropElement->dragAndDrop($draggable, $target)->perform();

        $this->saveChanges();

        $arrayDrag = $this->driver->findElements(WebDriverBy::cssSelector(".lines.ui-sortable tr td.cell.product.main.no-wrap"));

        foreach ($arrayDrag as $value) {

            $arrayDrop[] = $value->getText() ;

        }

        return $arrayDrop;

    }

}
