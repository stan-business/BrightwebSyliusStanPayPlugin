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
use Sylius\Component\Core\Model\OrderInterface;

use Brightweb\SyliusStanPayPlugin\Api;
use Brightweb\SyliusStanPayPlugin\Request\Api\CreateCustomer;

use Stan\Model\CustomerRequestBody;
use Stan\Model\Address;

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
        if (true === (bool) $this->api->options['only_for_stanner']) {
            return;
        }

        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $order = $request->getFirstModel()->getOrder();
        $customer = $order->getCustomer();
        $billingAddress = $order->getBillingAddress();

        $customerBody = new CustomerRequestBody();

        $customerAddress = new Address();
        $customerAddress = $customerAddress
            ->setFirstname($billingAddress->getFirstName())
            ->setLastname($billingAddress->getLastName())
            ->setStreetAddress($billingAddress->getStreet())
            // ->setStreetAddressLine2() TODO get line2 from shipping address
            ->setLocality($billingAddress->getCity())
            ->setZipCode($billingAddress->getPostcode())
            ->setCountry($billingAddress->getCountryCode());

        $customerBody = $customerBody
            ->setEmail($customer->getEmail())
            ->setName($billingAddress->getFullname())
            ->setAddress($customerAddress);

        $createdCustomer = $this->api->createCustomer($customerBody);

        $details->replace([
            'stan_customer_id' => $createdCustomer->getId()
        ]);
    }

    public function supports($request)
    {
        return $request instanceof CreateCustomer &&
            $request->getModel() instanceof ArrayAccess
        ;
    }
}