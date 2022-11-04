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

interface StanPayBridgeInterface
{
    public const STAN_API_URL = 'https://api.stan-app.fr';

    public const STAN_MODE_TEST = "TEST";
    public const STAN_MODE_LIVE = "LIVE";

    public function setAuthorizationData(
        string $environment,
        string $clientId,
        string $clientSecret,
        string $clientTestId,
        string $clientTestSecret
    ): void;

    public function preparePayment(PaymentRequestBody $data): PreparedPayment;

    public function getPayment(string $id): Payment;
}
