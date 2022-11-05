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

interface Api
{
    // public const STAN_API_URL = 'https://api.stan-app.fr';

    // public const STAN_MODE_TEST = "TEST";
    // public const STAN_MODE_LIVE = "LIVE";

    // public const PAYMENT_PREPARED = "payment_prepared";
    // public const PAYMENT_PENDING = "payment_pending";
    // public const PAYMENT_FAILURE = "payment_failure";
    // public const PAYMENT_SUCCESS = "payment_success";
    // public const PAYMENT_CANCELLED = "payment_cancelled";
    // public const PAYMENT_HOLDING = "payment_holding";

    // public function setAuthorizationData(
    //     string $environment,
    //     string $clientId,
    //     string $clientSecret,
    //     string $clientTestId,
    //     string $clientTestSecret
    // ): void;

    // public function preparePayment(PaymentRequestBody $data): PreparedPayment;

    // public function getPayment(string $paymentId): Payment;
}
