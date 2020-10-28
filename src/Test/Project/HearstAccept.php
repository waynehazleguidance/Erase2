<?php

declare(strict_types=1);

namespace Guidance\Tests\Project\Test\Project;

use Facebook\WebDriver\WebDriverKeys;

class DemoCest extends \Guidance\Tests\Base\Test\BaseAbstract
{
    private const INVALID_SEARCH_PRODUCT = 'invalid_non_existing_item';

    // ########################################

    protected function processStateAndPreconditions(): void
    {
        /**
         * ========================================
         *               EXAMPLE USE
         * ========================================
         */

        // ========================================Data registry

        $city1 = $this->dataGenerator->getCity();
        $city2 = $this->dataGenerator->getCity();

        $email         = $this->dataGenerator->getEmail();
        $country       = $this->dataGenerator->getCountry();
        $streetAddress = $this->dataGenerator->getStreetAddress();

        // ========================================Data provider

        $testIndividualData = $this->dataSetProviderIndividual->getData('/city_chic/PDP/id/');
        $testIndividualFile = $this->dataSetProviderIndividual->getFile('guid.png');

        $testGeneralData = $this->dataSetProviderGeneral->getData('/browser/chrome/extension/store/');
        $testGeneralFile = $this->dataSetProviderGeneral->getFile('/browser/chrome/extension/watermark.png');
    }

    // ########################################



    public function ShopCosmopolitan(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.cosmopolitan.com/');
   //     $I->reloadPage();
        $I->see('Cosmopolitan');

        // ******************************************
        // Search Valid and Invalid
        /// ***************************
        //  $I->wait(5);
        // $I->click(['css' => '.search__btn']);
         // $I->executeJs("document.getElementById('search').value='XXX';");  // Invalid Entry

        //$I->executeJs('document.querySelector(\'.search\').value = \'XXX\';');
        // Invalid Entry

         // $I->wait(9);
       // $I->executeJs("document.getElementById('search').value='Magazine';");  // Valid Entry


        // *****************************************************************************
        //  Add to Cart
        // ***********************************************************


        // Click Add to Cart
        $I->see('Add to Cart');
        $I->click(['css' => '#product-addtocart-button > span:nth-child(1)']);
        $I->waitForText('Added to cart!');

        // Continue Shopping
        $I->wait(5);
        $I->click(['css' => 'a.action:nth-child(5)']);
        // You added ... to your shopping cart.
        $I->waitForText('added');



    }

    public function _ShopWomensHeathMag(\Guidance\Tests\Project\Actor $I)
    {

        $I->amOnUrl('https://shop.womenshealthmag.com/');
        $I->see('Magazine');
        $I->wait(5);
        // ******************************************
        // Search Valid and Invalid
        /// ***************************
        //  $I->wait(5);
        // $I->click(['css' => '.search__btn']);
        // $I->executeJs("document.getElementById('search').value='XXX';");  // Invalid Entry

        //$I->executeJs('document.querySelector(\'.search\').value = \'XXX\';');
        // Invalid Entry

        // $I->wait(9);
        // $I->executeJs("document.getElementById('search').value='Magazine';");  // Valid Entry

        // *****************************************************************************
        //  GO TO PLP - Editor's Pick
        // ***********************************************************
        // 2. Go to Simple SHOP ALL PLP page----------------------------------------------------------------------------
        $I->amOnPage('/editors-picks.html');
        $I->see('Keto');

        //  Will add code to select filters

        // *****************************************************************************
        //  Go to a PDP and Add to Cart
        // ***********************************************************
        $I->amOnPage('/keto-for-carb-lovers.html');
        $I->see('Keto');


        // Click Add to Cart
        $I->see('Add to Cart');
        $I->click(['css' => '#product-addtocart-button > span:nth-child(1)']);
        $I->waitForText('Added to cart!');

        // Continue Shopping
        $I->wait(5);
        $I->click(['css' => 'a.action:nth-child(5)']);
        // You added ... to your shopping cart.
        $I->waitForText('added');

        // *****************************************************************************
        //  Go to Shopping Cart
        // ***********************************************************
        $I->amOnPage('/checkout/cart/');
        $I->see('Cart');

    }

    public function _ShopPrevention(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.prevention.com/');
        $I->see('Editor');

        // ******************************************
        // Search
        /// ***************************
       // $I->click(['css' => 'html body.customer-logged-out.cms-home.cms-index-index.page-layout-1column div.page-wrapper header.page-header div.header.content div.header-main-panel div.search button.search__btn.menu-search']); //
        // $I->click();
        //$I->wait(3);
        //$I->fillField(['id' => 'search'], "xxx");  // Invalid Entry
        //$I->wait(3);
      //  $I->fillField(['id' => 'search'], "Prevention");  // Valid Entry

        $I->wait(6);
        // *****************************************************************************
        //  Go to PLP & Test Sorting
        // ***********************************************************

        // 2. Go to  SHOP ALL PLP page and choose sorting drop downs
        $I->amOnPage('/shop-all.html');
        $I->see('Mini');
        $I->wait(2);
        $I->selectOption(['id' => 'sorter'], 'name');
        $I->wait(5);
        $I->selectOption(['id' => 'sorter'], 'price');
        $I->wait(5);

        // *****************************************************************************
        //  go to PDP and Add to Cart
        // ***********************************************************
        // 2. Go to Simple SHOP ALL PLP page----------------------------------------------------------------------------
        $I->amOnPage('/keto-for-carb-lovers.html');
        $I->see('Keto');


        // Click Add to Cart
        $I->see('Add to Cart');
        $I->click(['css' => '#product-addtocart-button > span:nth-child(1)']);
        $I->waitForText('Added to cart!');

        // Continue Shopping
        $I->wait(5);
        $I->click(['css' => 'a.action:nth-child(5)']);
        // You added ... to your shopping cart.
        $I->waitForText('added');

        // *****************************************************************************
        //  Go to Shopping Cart
        // ***********************************************************
        $I->amOnPage('/checkout/cart/');
        $I->see('Cart');

    }



    // **********************************
    public function _ShopElle(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.elle.com');
        $I->reloadPage();
        $I->see('Elle');

        // *****************************************************************************
        //  Add to Cart
        // ***********************************************************


        // Click Add to Cart
        $I->see('Add to Cart');
        $I->click(['css' => '#product-addtocart-button > span:nth-child(1)']);
        $I->waitForText('Added to cart!');

        // Continue Shopping
        $I->wait(5);
        $I->click(['css' => 'a.action:nth-child(5)']);
        // You added ... to your shopping cart.
        $I->waitForText('added');
        $I->wait(5);

        // *****************************************************************************
        //  Go to Shopping Cart
        // ***********************************************************
        $I->amOnPage('/checkout/cart/');
        $I->see('Cart');

    }

    public function _ShopMarieClaire(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.marieclaire.com/');
        $I->see('Marie');
        // Search, Add magazine on front page to cart,  go to checkout

    }

    public function _ShopMensHealth(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.menshealth.com/');
        $I->see('Editor');
        // Search, Add magazine on front page to cart,  go to checkout

    }

    public function _ShopEsquire(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.esquire.com/');
        $I->see('Esquire');

    }


    public function _ShopVeranda(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.veranda.com/veranda-magazine.html');
        $I->see('Veranda');

    }

    public function _ShopTownandCountry(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.townandcountrymag.com');
        $I->see('Town');

    }

    public function _ShopHarpersBazaar(\Guidance\Tests\Project\Actor $I)
    {

        $I->amOnUrl('https://store.harpersbazaar.com');
        $I->see('BAZAAR');

    }

    public function _ShopWomansDay(\Guidance\Tests\Project\Actor $I)
    {

        $I->amOnUrl('https://shop.womansday.com');
        $I->see('Woman');

    }

public function _ShopPioneerWoman(\Guidance\Tests\Project\Actor $I)
{
    // No content Except friont opage
    $I->amOnUrl('https://subscribe.hearstmags.com/subscribe/splits/pioneerwomanmag/pnw_splash');
    $I->see('Subscribe');

}

public function _ShopHouseBeautiful(\Guidance\Tests\Project\Actor $I)
{
    // No content Except friont opage
    $I->amOnUrl('https://subscribe.hearstmags.com/subscribe/splits/pioneerwomanmag/pnw_splash');
    $I->see('Subscribe');

}

public function _GoodHousekeeping(\Guidance\Tests\Project\Actor $I)
{
    // No content just front opage
    $I->amOnUrl('https://www.goodhousekeeping.com/');
    $I->see('Subscribe');

}

public function _CountryLiving(\Guidance\Tests\Project\Actor $I)
{
    // No content just front page
    $I->amOnUrl('https://www.countryliving.com/');
    $I->see('Subscribe');

}

public function _EllecDecor(\Guidance\Tests\Project\Actor $I)
{
    // No content just front page
    $I->amOnUrl('https://www.elledecor.com/');
    $I->see('Sign');

}


public function _TownandCountry(\Guidance\Tests\Project\Actor $I)
{
    // No content just front page
    $I->amOnUrl('https://www.townandcountrymag.com/');
    $I->see('Sign');

}


public function _RoadandTrack(\Guidance\Tests\Project\Actor $I)
{
    // No content just front page
    $I->amOnUrl('https://www.roadandtrack.com/');
    $I->see('Sign');

}


public function _PopularMechanics(\Guidance\Tests\Project\Actor $I)
{
    // No content just front page
    $I->amOnUrl('https://www.popularmechanics.com/');
    $I->see('Sign');

}

public function _Bicycling(\Guidance\Tests\Project\Actor $I)
{
    // No content just front page
    $I->amOnUrl('https://www.bicycling.com/');
    $I->see('Sign');

}

public function _CarandDriver(\Guidance\Tests\Project\Actor $I)
{
    // No content just front page
    $I->amOnUrl('https://www.caranddriver.com/');
    $I->see('Sign');

}


public function _RunnersWorld(\Guidance\Tests\Project\Actor $I)
{
    // No content just front page
    $I->amOnUrl('https://www.runnersworld.com/');
    $I->see('Sign');

}
    // ########################################
}
