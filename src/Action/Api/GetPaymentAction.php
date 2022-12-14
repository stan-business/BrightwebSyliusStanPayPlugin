<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin\Action\Api;

use ArrayAccess;
use Brightweb\SyliusStanPayPlugin\Api;
use Brightweb\SyliusStanPayPlugin\Request\Api\GetPayment;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;

class GetPaymentAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Api::class;
    }

    /**
     * @param GetPayment $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (false == $details['stan_payment_id']) {
            throw new LogicException('The parameter "stan_payment_id" must be set. Have you run PrepareAction?');
        }

        $payment = $this->api->getPayment($details['stan_payment_id']);

        $details->replace([
            'stan_payment_status' => $payment->getPaymentStatus(),
        ]);
    }

    public function supports($request): bool
    {
        return $request instanceof GetPayment &&
            $request->getModel() instanceof ArrayAccess
        ;
    }
}
