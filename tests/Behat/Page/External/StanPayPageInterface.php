<?php

declare(strict_types=1);

namespace Tests\Brightweb\SyliusStanPayPlugin\Behat\Page\External;

use FriendsOfBehat\PageObjectExtension\Page\PageInterface;

interface StanPayPageInterface extends PageInterface
{
    public function notify(string $content): void;
}