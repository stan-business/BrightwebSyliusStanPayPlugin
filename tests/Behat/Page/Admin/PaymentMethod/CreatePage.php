<?php

declare(strict_types=1);

namespace Tests\Brightweb\SyliusStanPayPlugin\Behat\Page\Admin\PaymentMethod;

use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\PaymentMethod\CreatePage as BaseCreatePage;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    protected function getDefinedElements(): array
    {
        return [];
    }

    public function setStanPayClientSecret(string $clientSecret): void
    {
        $this->getDocument()->fillField('Your API Client secret', $clientSecret);
    }

    public function setStanPayClientId(string $clientId): void
    {
        $this->getDocument()->fillField('Your API Client ID', $clientId);
    }
}