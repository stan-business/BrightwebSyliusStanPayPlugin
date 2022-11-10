<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin\Action\Api;

use ArrayAccess;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;

use Brightweb\SyliusStanPayPlugin\Api;
use Brightweb\SyliusStanPayPlugin\Request\Api\CreateCustomer;

class CreateCustomerAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Api::class;
    }

    /**
     * @param CreateCustomer $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        // TODO create customer

        // $details = ArrayObject::ensureArrayObject($request->getModel());

        // if (false == $details['stan_payment_id']) {
        //     throw new LogicException('The parameter "stan_payment_id" must be set. Have you run PrepareAction?');
        // }

        // $payment = $this->api->getPayment($details['stan_payment_id']);

        // $details->replace([
        //     'stan_payment_status' => $payment->getPaymentStatus()
        // ]);
    }

    public function supports($request)
    {
        return $request instanceof CreateCustomer &&
            $request->getModel() instanceof ArrayAccess
        ;
    }
}