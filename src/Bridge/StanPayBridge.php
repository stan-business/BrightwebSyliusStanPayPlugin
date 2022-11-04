<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin\Bridge;

use Stan\Model\Payment;
use Stan\Model\PreparedPayment;
use Stan\Model\PaymentRequestBody;
use Stan\Api\StanClient;
use Stan\Configuration;

final class StanPayBridge implements StanPayBridgeInterface
{
    /*** @var string|null */
    private $cacheDir;

    /*** @var Stan\Configuration */
    private $apiConfig;

    public function __construct(string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir;

        $this->apiConfig = new Configuration();
    }

    public function setAuthorizationData(
        string $environment,
        string $clientId,
        string $clientSecret,
        string $clientTestId,
        string $clientTestSecret
    ): void {
        $confApiClientId = $environment === StanPayBridgeInterface::STAN_MODE_TEST
            ? $clientTestId
            : $clientId;
    
        $confApiClientSecret = $environment === StanPayBridgeInterface::STAN_MODE_TEST
            ? $clientTestSecret
            : $clientSecret;

        $this->apiConfig
            ->setHost('https://api-staging.stan-app.fr/v1')
            ->setClientId($confApiClientId)
            ->setClientSecret($confApiClientSecret);
    }

    public function preparePayment(PaymentRequestBody $paymentBody): PreparedPayment
    {
        $apiClient = $this->getApiClient();
        return $apiClient->paymentApi->create($paymentBody);
    }

    public function getPayment(string $id): Payment
    {
        // TODO
    }

    private function getApiClient(): StanClient
    {
        return new StanClient($this->apiConfig);
    }
}