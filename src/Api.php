<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin;

use Stan\Model\Payment;
use Stan\Model\PreparedPayment;
use Stan\Model\PaymentRequestBody;
use Stan\Api\StanClient;
use Stan\Configuration;

class Api
{
    public const STAN_API_URL = 'https://api-staging.stan-app.fr/v1';

    public const STAN_MODE_TEST = "TEST";
    public const STAN_MODE_LIVE = "LIVE";

    public const PAYMENT_PREPARED = "payment_prepared";
    public const PAYMENT_PENDING = "payment_pending";
    public const PAYMENT_FAILURE = "payment_failure";
    public const PAYMENT_SUCCESS = "payment_success";
    public const PAYMENT_CANCELLED = "payment_cancelled";
    public const PAYMENT_HOLDING = "payment_holding";

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function preparePayment(PaymentRequestBody $paymentBody): PreparedPayment
    {
        $apiClient = $this->getApiClient();
        var_dump($paymentBody);
        return $apiClient->paymentApi->create($paymentBody);
    }

    public function getPayment(string $paymentId): Payment
    {
        $apiClient = $this->getApiClient();
        return $payment = $apiClient->paymentApi->getPayment($paymentId);
    }

    private function getApiClient(): StanClient
    {
        $environment = $this->options['environment'];

        $confApiClientId = $environment === self::STAN_MODE_TEST
            ? $this->options['client_test_id']
            : $$this->options['client_id'];
    
        $confApiClientSecret = $environment === self::STAN_MODE_TEST
            ? $this->options['client_test_secret']
            : $this->options['client_secret'];

        $apiConfig = new Configuration();
        $apiConfig
            ->setHost(self::STAN_API_URL)
            ->setClientId($confApiClientId)
            ->setClientSecret($confApiClientSecret);

        return new StanClient($apiConfig);
    }
}