<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
 */

declare(strict_types=1);

namespace Tests\Brightweb\SyliusStanPayPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use RuntimeException;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Tests\Brightweb\SyliusStanPayPlugin\Behat\Mocker\StanPayApiMocker;
use Tests\Brightweb\SyliusStanPayPlugin\Behat\Page\External\StanPayPage;

class StanPayShopContext extends MinkContext implements Context
{
    /** @var StanPayApiMocker */
    private $stanPayApiMocker;

    /** @var CompletePageInterface */
    private $summaryPage;

    /** @var ShowPageInterface */
    private $orderDetails;

    /** @var StanPayPage */
    private $paymentPage;

    public function __construct(
        StanPayApiMocker $stanPayApiMocker,
        CompletePageInterface $summaryPage,
        ShowPageInterface $orderDetails,
        StanPayPage $paymentPage
    ) {
        $this->stanPayApiMocker = $stanPayApiMocker;
        $this->summaryPage = $summaryPage;
        $this->orderDetails = $orderDetails;
        $this->paymentPage = $paymentPage;
    }

    /**
     * @When I confirm my order with Stan Pay payment
     * @Given I have confirmed my order with Stan Pay payment
     */
    public function iConfirmMyOrderWithStanPayPayment()
    {
        $this->stanPayApiMocker->mockPreparePayment(function () {
            $this->summaryPage->confirmOrder();
        });
    }

    /**
     * @When I get redirected to Stan Pay and complete my payment
     */
    public function iGetRedirectedToStanPay(): void
    {
       // TODO
    }
    
    /**
     * @Given I have clicked on "go back" during my Stan Pay payment
     * @When I click on "go back" during my Stan Pay payment
     */
    public function iClickOnGoBackDuringMyStanPayPayment()
    {
        $this->stanPayApiMocker->mockGoBackPayment(function () {
            $this->paymentPage->captureOrAuthorizeThenGoToAfterUrl();
        });
    }

    /**
     * @When I try to pay again with Stan Pay payment
     */
    public function iTryToPayAgainWithStanPayPayment(): void
    {
        $this->stanPayApiMocker->mockPreparePayment(function () {
            $this->orderDetails->pay();
        });
    }

    private function assertNotification(string $expectedNotification)
    {
        $notifications = $this->orderDetails->getNotifications();
        $hasNotifications = '';

        foreach ($notifications as $notification) {
            $hasNotifications .= $notification;
            if ($notification === $expectedNotification) {
                return;
            }
        }

        throw new RuntimeException(sprintf('There is no notification with "%s". Got "%s"', $expectedNotification, $hasNotifications));
    }
}