<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
 */

declare(strict_types=1);

namespace Tests\Brightweb\SyliusStanPayPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\UnexpectedPageException;
use Tests\Brightweb\SyliusStanPayPlugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;
use Webmozart\Assert\Assert;

class ManagingPaymentMethodsContext implements Context
{
    /** @var CreatePageInterface */
    private $createPage;

    public function __construct(CreatePageInterface $createPage)
    {
        $this->createPage = $createPage;
    }

    /**
     * @Given /^I want to create a new Stan Pay payment method$/
     *
     * @throws UnexpectedPageException
     */
    public function iWantToCreateANewStanPayPaymentMethod(): void
    {
        $this->createPage->open(['factory' => 'stan_pay']);
    }

    /**
     * @When I configure it with Stan Pay gateway data :clientId, :clientSecret
     */
    public function iConfigureItWithStanPayGatewayData(string $clientId, string $clientSecret)
    {
        $this->createPage->setStanPayClientId($clientId);
        $this->createPage->setStanPayClientSecret($clientSecret);
    }
}