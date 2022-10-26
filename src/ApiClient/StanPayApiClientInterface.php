<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin\ApiClient;

use MultiSafepayAPI\Object\Orders;

interface StanPayApiClientInterface
{
    public const STAN_API_URL = 'https://api.stan-app.fr';

    public const STAN_MODE_TEST = "TEST";
    public const STAN_MODE_LIVE = "LIVE";

    public function initialise(
        string $apiKey,
        string $type,
        bool $sandbox = true,
        bool $allowMultiCurrency = false
    ): void;

    public function createPayment(array $data): Orders;

    public function getOrderById(string $id): \stdClass;

    public function getType(): string;

    public function refund(
        string $orderId,
        int $amount,
        string $currencyCode
    ): void;

    public function isPaymentActive(string $status): bool;

    public function getAllowMultiCurrency(): bool;
}
