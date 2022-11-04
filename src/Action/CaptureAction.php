<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin\Action;

use ArrayAccess;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Sync;
use Payum\Core\Request\Capture;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;
use Sylius\Bundle\PayumBundle\Model\PaymentSecurityToken;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

use Brightweb\SyliusStanPayPlugin\Bridge\StanPayBridgeInterface;
use Stan\Model\PaymentRequestBody;
use Stan\Model\CustomerRequestBody;

class CaptureAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    private $stanPayBridge;

    public function __construct(StanPayBridgeInterface $stanPayBridge)
    {
        $this->stanPayBridge = $stanPayBridge;
    }

    /**
     * @throws UnsupportedApiException if the given Api is not supported.
     */
    public function setApi($api): void
    {
        if (false === is_array($api)) {
            throw new UnsupportedApiException('Not supported. Expected to be set as array.');
        }

        $this->stanPayBridge->setAuthorizationData(
            $api['environment'],
            $api['client_id'],
            $api['client_secret'],
            $api['client_test_id'],
            $api['client_test_secret']
        );
    }

    /**
     * @param Capture $request
     */
    public function execute($request)
    {
        /** @var Capture $request */
        RequestNotSupportedException::assertSupports($this, $request);
        $model = $request->getModel();

        /** @var OrderInterface $orderData */
        $order = $request->getFirstModel()->getOrder();

        /** @var TokenInterface $token */
        $token = $request->getToken();
        $paymentBody = $this->preparePayment($token, $order);

        $preparedPayment = $this->stanPayBridge->preparePayment($paymentBody);

        throw new HttpRedirect($preparedPayment->getRedirectUrl());
    }

    public function supports($request)
    {
        return $request instanceof Capture &&
            $request->getModel() instanceof ArrayAccess
        ;
    }

    public function preparePayment(PaymentSecurityToken $token, OrderInterface $order): PaymentRequestBody
    {
        $paymentBody = new PaymentRequestBody();

        $paymentBody
            ->setOrderId($order->getNumber())
            ->setAmount($order->getTotal())
            ->setReturnUrl($token->getTargetUrl())
            ->setState(
                $this->tokenFactory->createNotifyToken(
                    $token->getGatewayName(),
                    $token->getDetails()
                )
                ->getHash()
            );

        return $paymentBody;
    }

    public function prepareCustomer(CustomerInterface $customer): CustomerRequestBody
    {
        // TODO
    }
}