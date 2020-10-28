<?php

declare(strict_types=1);

namespace Guidance\Tests\Project\Test\Project;

use Facebook\WebDriver\WebDriverKeys;

class Hearst extends \Guidance\Tests\Base\Test\BaseAbstract
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

    /**
     * @group CityChick
     * @before processStateAndPreconditions
     */

    public function ShopMensHealth(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.menshealth.com/');
        $I->reloadPage();
        $I->see('Editor');

    }

    public function ShopEsquire(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.esquire.com/');
        $I->reloadPage();
        $I->see('Esquire');

    }

    public function ShopPrevention(\Guidance\Tests\Project\Actor $I)
    {


        $I->amOnUrl('https://shop.prevention.com/');
        $I->reloadPage();
        $I->see('Editor');

    }



    public function cityChicTest(\Guidance\Tests\Project\Actor $I)
    {
        $login        = $this->dataSetProviderIndividual->getData('/city_chic/customer/login/');
        $password     = $this->dataSetProviderIndividual->getData('/city_chic/customer/password/');
        $productId    = $this->dataSetProviderIndividual->getData('/city_chic/PDP/id/');
        $productTitle = $this->dataSetProviderIndividual->getData('/city_chic/PDP/title/');

        $I->amOnUrl('https://cc-m2.stage.guidance.com/');
        $I->reloadPage();

        // 1. Login as customer
        $I->amOnPage('/customer/account/login');
        $I->see('Login or Create an Account');

        $I->fillField(['id' => 'email'], $login);
        $I->fillField(['id' => 'pass'], $password);

        $I->scrollTo(['css' => '.login-container button[type="submit"]'], 0, -400);
        $I->click(['css' => '.login-container button[type="submit"]']);

        $I->seeInCurrentUrl('/customer/account');
        $I->see('MY ACCOUNT');

        // 2. Go to PLP page
        $I->amOnPage('/dresses');
        $I->see('dresses');

        $lowPrice  = $I->executeJS('return jQuery(".product-item:first-child [data-price-type=\"finalPrice\"] .price").text()');
        $highPrice = $I->executeJS('return jQuery(".product-item:last-child [data-price-type=\"finalPrice\"] .price").text()');

        // 3. Check search
        //TODO: Bug with search result page layout. Need to fix.
        $I->click(['css' => '.showsearch']);
        $I->waitForElement(['css' => '.showsearch.active'], 5);

        $I->fillField(['css' => 'input[id^="sli_search"]'], self::INVALID_SEARCH_PRODUCT);
        $I->pressKey('input[id^="sli_search"]',WebDriverKeys::ENTER);

        $I->waitForText('search results for: \'' . self::INVALID_SEARCH_PRODUCT . '\'');
        $I->see('Search was unable to find any results');

        $I->click(['css' => '.showsearch']);
        $I->waitForElement(['css' => '.showsearch.active'], 5);

        $I->fillField(['css' => 'input[id^="sli_search"]'], $productTitle);
        $I->pressKey('input[id^="sli_search"]',WebDriverKeys::ENTER);

        $I->waitForText('search results for: \'' . strtolower($productTitle) . '\'');
        $I->dontSee('Search was unable to find any results');

        $I->assertGreaterThan(0, $I->executeJS('return jQuery(".products-grid ol.product-items > li").length'));

        // 4. Go to PDP page and add item to cart
        $I->amOnPage('/catalog/product/view/id/' . $productId);
        $I->waitForText($productTitle);

        $I->click(['id' => 'product-addtocart-button']);
        $I->waitForText('You added ' . $productTitle);

        // 5. Go to cart > Proceed checkout
        $I->click(['css' => '.action.showcart']);
        $I->waitForElement(['css' => '.minicart-wrapper.active']);
        $I->click(['css' => '.action.viewcart']);

        $I->see('YOUR BAG');
        $I->see($productTitle);

        $I->waitForElement(['css' => '.page-title-wrapper [data-role="proceed-to-checkout"]:not([disabled="disabled"])']);
        $I->click(['css' => '.page-title-wrapper [data-role="proceed-to-checkout"]:not([disabled="disabled"])']);

        $I->waitForNoActiveAjax();
        $I->click(['css' => '#billing .btn-next']);

        $I->waitForNoActiveAjax();
        $I->click(['css' => '#opc-shipping_method .btn-next']);

        $I->waitForNoActiveAjax();
        $I->click(['css' => 'label[for="braintree_paypal"]']);

        $I->waitForNoActiveAjax();
        $I->click(['css' => '#payment .btn-next']);

        $I->waitForNoActiveAjax();
        $I->scrollTo(['css' => '.step-review [title="Continue to PayPal"]'], 0, -400);
        $I->click(['css' => '.step-review [title="Continue to PayPal"]']);

        $windows = $I->getWindowHandles();

        $I->switchToWindow(end($windows));
        $I->waitForElement(['id' => 'return_url']);
        $I->wait(1);
        $I->click(['id' => 'return_url']);

        $I->switchToWindow(reset($windows));

        $I->waitForText($this->dataSetProviderIndividual->getData('/city_chic/checkout/paypal_email/'));
        $I->click(['css' => '.step-review [title="Place Order"]']);

        $I->waitForText('YOUR ORDER HAS BEEN RECEIVED');
    }

    /**
     * @group WallyWine
     */
    public function _wallyWineTest(\Guidance\Tests\Project\Actor $I)
    {
        $login            = $this->dataSetProviderIndividual->getData('/wally_wine/customer/login/');
        $password         = $this->dataSetProviderIndividual->getData('/wally_wine/customer/password/');
        $customerFullName = $this->dataSetProviderIndividual->getData('/wally_wine/customer/full_name/');
        $productLink      = $this->dataSetProviderIndividual->getData('/wally_wine/PDP/link/');
        $productTitle     = $this->dataSetProviderIndividual->getData('/wally_wine/PDP/title/');

        $I->amOnUrl('https://stage.wallywine.com/');

        // 1. Login as customer
        $I->amOnPage('/customer/account/login/');
        $I->see('SIGN IN');

        $I->fillField(['css' => 'input[name="login[username]"]'], $login);
        $I->fillField(['css' => 'input[name="login[password]"]'], $password);

        $I->click(['css' => 'button[title="Wally\'s Login"]']);

        $I->see('Welcome back, ' . $customerFullName);
        $I->see('Account Information');

        // 2. Go to PLP page
        $I->amOnPage('/spirits.html');
        $I->seeInTitle('Spirits');

        // 3. Check search
        $I->fillField(['id' => 'search'], self::INVALID_SEARCH_PRODUCT);
        $I->pressKey(['id' => 'search'],WebDriverKeys::ENTER);

        $I->see('Your search for "' . self::INVALID_SEARCH_PRODUCT . '" returned no results.');

        $I->fillField(['id' => 'search'], $productTitle);
        $I->pressKey(['id' => 'search'],WebDriverKeys::ENTER);

        $I->see('results for "' . $productTitle . '"');

        $I->assertGreaterThan(0, $I->executeJS('return jQuery(".products-grid > li").length'));

        // 4. Go to PDP page and add item to cart
        $I->amOnPage($productLink);
        $I->see($productTitle);

        $productQty = $this->dataGenerator->getNumberBetween(1, 3);
        $cartQty = (int) $I->executeJS('return jQuery(".count").text()');

        if ($cartQty > 0) {
            $I->click(['css' => 'a.top-link-cart']);
            $I->waitForElement(['css' => 'div#header-cart.skip-active'], 5);

            $I->click(['css' => 'a[title="Remove This Item"]']);
            $I->acceptPopup();

            $I->waitForJS('return jQuery(".count").text() === "0"');
        }

        $I->fillField(['id' => 'qty'], $productQty);

        //TODO: Bug on adding already purchased product. Need to fix.
        $I->click(['css' => 'button[title="Add to Cart"]']);
        $I->waitForElement(['css' => 'div#header-cart.skip-active']);

        $I->see($productQty, ['css' => '.top-link-cart .count']);
        $I->see($productQty);

        // 5. Go to cart > Proceed checkout
        $I->click(['css' => 'a[title="Check Out"]']);

        $I->see('Shopping Bag');
//        $I->see($productTitle);

        $I->click(['css' => 'button[title="Check Out"]']);
        $I->seeInTitle('Checkout');

        $I->waitForElement(['css' => '#opc-billing.active']);
        $I->click(['css' => '#opc-billing button[title="Continue"]']);

        $I->waitForElement(['css' => '#opc-shipping_method.active']);

        $shippingMethodsIds = [
            's_method_storepickup_storepickup',
            's_method_fedex_FEDEX_GROUND_insurance',
            's_method_fedex_PRIORITY_OVERNIGHT_insurance',
            's_method_fedex_FEDEX_EXPRESS_SAVER_insurance',
            's_method_fedex_FEDEX_2_DAY_insurance',
            's_method_fedex_FEDEX_2_DAY_insurance',
            's_method_fedex_FIRST_OVERNIGHT_insurance'
        ];

        $I->click(['css' => 'label[for="' . $shippingMethodsIds[array_rand($shippingMethodsIds)] . '"]']);

        $I->click(['css' => '#opc-shipping_method button']);
        $I->waitForElement(['css' => '#opc-payment.active']);

        $I->click(['css' => 'label[for="p_method_authnetcim"]']);
        $I->waitForElementVisible(['id' => 'payment_form_authnetcim']);

        try {
            $I->fillField(['id' => 'authnetcim_cc_number'], $this->dataGenerator->getTestSpecificVisa());
            $I->selectOption(['id' => 'authnetcim_cc_type'], 'VI');
            $I->selectOption(['id' => 'authnetcim_expiration'], '12');
            $I->selectOption(['id' => 'authnetcim_expiration_yr'], '2029');
            $I->fillField(['id' => 'authnetcim_cc_cid'], $this->dataGenerator->getNumberBetween(100, 999));

        } catch (\Exception $e) {}

        $I->click(['css' => '#opc-payment button']);
        $I->waitForElement(['css' => '#opc-review.active']);

        $I->click(['id' => 'agreement-one']);
        $I->click(['css' => 'button[title="Place Order"]']);

        $I->waitForText('Thank you for your order', 20);
    }

    /**
     * @group HeartMath
     */
    public function _heartMathTest(\Guidance\Tests\Project\Actor $I)
    {
        $login            = $this->dataSetProviderIndividual->getData('/heart_math/customer/login/');
        $password         = $this->dataSetProviderIndividual->getData('/heart_math/customer/password/');
        $customerFullName = $this->dataSetProviderIndividual->getData('/heart_math/customer/full_name/');
        $productLink      = $this->dataSetProviderIndividual->getData('/heart_math/PDP/link/');
        $productTitle     = $this->dataSetProviderIndividual->getData('/heart_math/PDP/title/');

        $I->amOnUrl('https://heartmathsandbox.mybigcommerce.com/');

        // 1. Login as customer
        $I->click(['css' => '.navUser-action']);
        $I->waitForElement(['id' => 'login-form']);

        $I->fillField(['css' => 'input[name="email"]'], $login);
        $I->fillField(['css' => 'input[name="pass"]'], $password);

        $I->click(['css' => 'button[name="btn-login"]']);

        $I->see('Welcome back, ' . $customerFullName);

        // 2. Go to PLP page
        $I->amOnPage('/technology-products');
        $I->seeInTitle('Technology Products');

        // 3. Check search
        $I->click(['css' => 'a[title="Search"]']);

        $I->waitForElement(['id' => 'search_query']);

        $I->fillField(['id' => 'search_query'], self::INVALID_SEARCH_PRODUCT);
        $I->click(['id' => 'search-store']);

        $I->assertEquals(0, $I->executeJS('return jQuery(".productGrid > li").length'));

        $I->fillField(['id' => 'search_query'], $productTitle);
        $I->pressKey(['id' => 'search_query'],WebDriverKeys::ENTER);

        $I->assertGreaterThan(0, $I->executeJS('return jQuery(".productGrid > li").length'));

        // 4. Go to PDP page and add item to cart
        $I->amOnPage($productLink);
        $I->see($productTitle);

        $productQty = $this->dataGenerator->getNumberBetween(1, 3);

        $I->fillField(['css' => 'input[id^="qty"]'], $productQty);

        $I->click(['id' => 'form-action-addToCart']);

        $I->waitForText('Your Cart ('. $productQty * 2 . ' items)');
        $I->click(['xpath' => '//a[text() = "Check out"]']);

        // 5. Proceed checkout
        $I->waitForElement(['css' => '.checkout-step--shipping .checkout-view-content']);
        $I->scrollTo(['id' => 'checkout-shipping-continue'], 0 -300);

        try {
            $I->waitForElement(['css' => '#checkout-shipping-continue[disabled=""]']);
            $I->waitForElementNotVisible(['css' => '#checkout-shipping-continue[disabled=""]']);
        } catch (\Exception $e) {
            $I->waitForNoActiveAjax();
        }

        $I->click(['id' => 'checkout-shipping-continue']);

        $I->waitForElement(['css' => '.checkout-step--payment .checkout-view-content']);

        $I->waitForElementNotVisible(['css' => '#checkout-payment-continue[disabled=""]']);

        $I->click(['css' => 'label[for="radio-testgateway"]']);
        $I->wait(1);

        $I->fillField(['css' => 'input[name="ccNumber"]'], $this->dataGenerator->getTestSpecificVisa());
        $I->fillField(['css' => 'input[name="ccExpiry"]'], '12 / 28');
        $I->fillField(['css' => 'input[name="ccName"]'], $customerFullName);
        $I->fillField(['css' => 'input[name="ccCvv"]'], '123');

        $I->click(['id' => 'checkout-payment-continue']);

        $I->waitForText('Thank you Maksim!', 20);
    }

    /**
     * @group Popcultcha
     */
    public function _popcultchaTest(\Guidance\Tests\Project\Actor $I)
    {
        $login            = $this->dataSetProviderIndividual->getData('/popcultcha/customer/login/');
        $password         = $this->dataSetProviderIndividual->getData('/popcultcha/customer/password/');
        $customerFullName = $this->dataSetProviderIndividual->getData('/popcultcha/customer/full_name/');
        $plpLink          = $this->dataSetProviderIndividual->getData('/popcultcha/PLP/link/');
        $plpTitle         = $this->dataSetProviderIndividual->getData('/popcultcha/PLP/title/');
        $productLink      = $this->dataSetProviderIndividual->getData('/popcultcha/PDP/link/');
        $productTitle     = $this->dataSetProviderIndividual->getData('/popcultcha/PDP/title/');

        $I->amOnUrl('http://dev01.popcultcha.dev.guidance.com/');
        $I->waitForElementVisible(['css' => '[role="dialog"][aria-live="polite"]', 40]);
        $I->click(['css' => '[role="dialog"][aria-live="polite"] a.cc-dismiss']);
        $I->waitForElementNotVisible(['css' => '[role="dialog"][aria-live="polite"]']);

        // 1. Login as customer
        $I->waitForElement(['css' => '.header.content .customer-name[aria-haspopup="true"]'], 40);

        $I->click(['css' => '.customer-name']);
        $I->waitForElement(['css' => '.customer-name.active']);
        $I->click(['css' => '.authorization-link']);

        $I->fillField(['css' => 'input[name="login[username]"]'], $login);
        $I->fillField(['css' => 'input[name="login[password]"]'], $password);

        $I->click(['css' => '#login-form[novalidate="novalidate"] button[name="send"]']);

        $I->see('My Dashboard');
        $I->see('Hello, ' . $customerFullName);

        // 2. Go to PLP page
        $I->amOnPage($plpLink);
        $I->see($plpTitle);

        // 3. Check search
        $I->fillField(['id' => 'search'], self::INVALID_SEARCH_PRODUCT);
        $I->pressKey(['id' => 'search'],WebDriverKeys::ENTER);

        $I->see('Search results for: \'' . self::INVALID_SEARCH_PRODUCT .  '\'');
        $I->see('Your search returned no results.');

        $I->fillField(['id' => 'search'], $productTitle);
        $I->pressKey(['id' => 'search'],WebDriverKeys::ENTER);

        $I->assertGreaterThan(2, $I->executeJS('return jQuery(".product-items > li").length'));

        // 4. Go to PDP page and add item to cart
        $I->amOnPage($productLink);
        $I->see($productTitle);

        $productQty = $this->dataGenerator->getNumberBetween(1, 3);
        $I->waitForJS('return document.querySelector(".counter-number").innerText !== ""');
        $cartQty = $I->executeJS('return parseInt(document.querySelector(".counter-number").innerText)');

        if ($cartQty > 0) {
            $I->click(['css' => '.minicart-wrapper']);
            $I->waitForElement(['css' => '.minicart-wrapper.active']);

            $I->click(['css' => 'a[title="Remove item"]']);
            $I->waitForElement(['css' => '.modal-popup.confirm._show']);
            $I->wait(1);
            $I->click(['css' => '.action-accept']);

            $I->waitForJS('return jQuery(".counter-number").text() === "0"', 20);
            $I->waitForText('You have no items in your shopping cart.');
            $I->click(['id' => 'btn-minicart-close']);
            $I->moveMouseOver(['css' => '.header.content']);
        }

        $I->fillField(['id' => 'qty'], $productQty);

        $I->click(['id' => 'product-addtocart-button-clone']);

        $I->waitForText('You added ' . $productTitle, 20);
        $I->waitForJS('return parseInt(document.querySelector(".counter-number").innerText) === ' . $productQty);

        // 5. Proceed checkout

        $I->click(['css' => '.minicart-wrapper']);
        $I->waitForElement(['css' => '.minicart-wrapper.active']);

        $I->click(['id' => 'top-cart-btn-checkout']);

        $I->waitForElementNotVisible(['id' => 'checkout-loader']);

        $I->click(['css' => '#s_method_freeshipping_freeshipping ~ em']);
        $I->click(['css' => '.button.action.continue']);

        $I->waitForElementNotVisible(['id' => 'checkout-loader']);

        $I->click(['id' => 'checkmo']);

        $I->click(['css' => '.continue-to-review-step button[type="submit"]']);
        $I->waitForElement(['css' => '.cart-items']);

        $I->click(['css' => '[data-bind="click: placeOrder"]']);

        $I->waitForText('Thank You!', 20);
        $I->see('Your order has been received.');
    }

    /**
     * @group InsectLore
     */
    public function insectLoreTest(\Guidance\Tests\Project\Actor $I)
    {
        $siteAccessPassword = $this->dataSetProviderIndividual->getData('/insect_lore/site_access_password/');
        $login              = $this->dataSetProviderIndividual->getData('/insect_lore/customer/login/');
        $password           = $this->dataSetProviderIndividual->getData('/insect_lore/customer/password/');
        $plpLink            = $this->dataSetProviderIndividual->getData('/insect_lore/PLP/link/');
        $plpTitle           = $this->dataSetProviderIndividual->getData('/insect_lore/PLP/title/');
        $productLink        = $this->dataSetProviderIndividual->getData('/insect_lore/PDP/link/');
        $productTitle       = $this->dataSetProviderIndividual->getData('/insect_lore/PDP/title/');

        $I->amOnUrl('https://insectlore-staging.myshopify.com/');
        $I->seeInCurrentUrl('/password');

        $I->click(['css' => '#open-me > a']);
        $I->waitForElement(['css' => '.overlay.overlay-open']);

        $I->fillField(['id' => 'password'], $siteAccessPassword);
        $I->click(['css' => '#login_form .sign_up']);
        $I->seeInCurrentUrl('/');

        // 1. Login as customer
        $I->click(['css' => '.icon-user']);
        $I->waitForText('Customer Login', 20);

        $I->fillField(['id' => 'customer_email'], $login);
        $I->fillField(['id' => 'customer_password'], $password);

        $I->click(['css' => '#login_form .btn']);
        $I->waitForText('Account Details', 20);

        // 2. Go to PLP page
        $I->amOnPage($plpLink);
        $I->seeInTitle($plpTitle);

        // 3. Check search

        $I->click(['css' => '.menu a[title="Search"]']);
        $I->waitForElement(['css' => '.main_nav .dropdown_container.active']);
        $I->fillField(['css' => '.main_nav .search-terms'], self::INVALID_SEARCH_PRODUCT);
        $I->click(['css' => '.main_nav .search-submit']);

        $I->waitForText('Search', 20, ['css' => 'h1']);
        $I->see('Sorry, No Results!');

        $I->click(['css' => '.menu a[title="Search"]']);
        $I->waitForElement(['css' => '.main_nav .dropdown_container.active']);
        $I->fillField(['css' => '.main_nav .search-terms'], $productTitle);
        $I->pressKey(['css' => '.main_nav .search-terms'],WebDriverKeys::ENTER);

        $I->assertGreaterThan(0, $I->executeJS('return jQuery(".thumbnail").length'));

        // 4. Go to PDP page and add item to cart
        $I->amOnPage($productLink);
        $I->see($productTitle);

        $productQty = $this->dataGenerator->getNumberBetween(1, 3);

        $I->fillField(['id' => 'quantity'], $productQty);
        $I->click(['css' => '.purchase-details .add_to_cart']);

        $I->waitForElement(['css' => '.cart_container.active_link']);
        $I->assertEquals($productQty, (int) $I->executeJS('return jQuery("header:not(#header) .cart_count").text()'));

        // 5. Proceed checkout
        $I->click(['css' => 'header:not(#header) .add_to_cart']);
        $I->see('Contact information');

        $I->click(['css' => 'button[class*="continue-btn"]']);
        $I->see('Shipping method');

        $I->waitForElementNotVisible(['css' => '.content-box.blank-slate']);
        $I->waitForElement(['css' => '.section--shipping-method [data-shipping-methods]']);

        $I->click(['css' => 'button[class*="continue-btn"]']);
        $I->see('Payment');

        $I->waitForElement(['css' => '.section--payment-method .card-fields-container--loaded']);

        $cardData = [
            'number'             => $this->dataGenerator->getTestSpecificVisa(),
            'name'               => $this->dataGenerator->getName(),
            'expiry'             => $this->dataGenerator->getCreditCardExpirationDateString(),
            'verification_value' => $this->dataGenerator->getNumberBetween(100, 999),
        ];

        $nthChild = 2;
        foreach ($cardData as $key => $value) {

            $iFrameName = $I->executeJS('return jQuery("[data-credit-card-fields] .field:nth-child(' . $nthChild .') iframe").attr("name")');
            $I->switchToIFrame($iFrameName);

            $I->fillField(['id' => $key], $value);
            $I->switchToIFrame();

            $nthChild++;
        }

        $I->click(['css' => '.shown-if-js button[class*="continue-btn"]']);

        $I->waitForText('Thank you Maksim!', 30);
    }

    // ########################################
}
