<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin\Bridge;

use Stan\Model\Payment;

final class StanPayBridge implements StanPayBridgeInterface
{
    /*** @var string|null */
    private $cacheDir;

    public function __construct(string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir;
    }

    public function setAuthorizationData(
        string $environment,
        string $signatureKey,
        string $clientId,
        string $clientSecret
    ): void {
        // TODO
    }

    public function preparePayment(array $data): Payment
    {
        // TODO
    }

    public function getPayment(string $id): Payment
    {
        // TODO
    }
}