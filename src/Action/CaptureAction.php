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

use Brightweb\SyliusStanPayPlugin\Api;
use Brightweb\SyliusStanPayPlugin\Request\Api\PreparePayment;
use Brightweb\SyliusStanPayPlugin\Request\Api\CreateCustomer;

class CaptureAction implements ActionInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    /**
     * @param Capture $request
     */
    public function execute($request)
    {
        /** @var Capture $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = $request->getModel();
        $details = ArrayObject::ensureArrayObject($request->getModel());

        /** @var OrderInterface $orderData */
        $order = $request->getFirstModel()->getOrder();

        // creates a payment
        if (null === $details['stan_payment_id']) {
            /** @var TokenInterface $token */
            $token = $request->getToken();

            if ($token) {
                $details['return_url'] = $token->getTargetUrl();
            }

            $notifyToken = $this->tokenFactory->createNotifyToken(
                $token->getGatewayName(),
                $token->getDetails()
            );

            $details['order_id'] = $order->getNumber();
            $details['token_hash'] = $notifyToken->getHash();
            $details['int_amount'] = $order->getTotal();

            $this->gateway->execute(new CreateCustomer($request->getFirstModel()));
            $this->gateway->execute(new PreparePayment($details));
        }

        $this->gateway->execute(new Sync($details));
    }

    public function supports($request)
    {
        return $request instanceof Capture &&
            $request->getModel() instanceof ArrayAccess
        ;
    }
}