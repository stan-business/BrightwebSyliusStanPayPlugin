<?php

declare(strict_types=1);

namespace Tests\Brightweb\SyliusStanPayPlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\PaymentMethod\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function setStanPayClientSecret(string $secretKey): void;

    public function setStanPayClientId(string $publishableKey): void;
}