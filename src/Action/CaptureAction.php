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
use Payum\Core\Request\Capture;
use Payum\Core\Request\Sync;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;

use Brightweb\SyliusStanPayPlugin\Bridge\StanPayBridgeInterface;

// TODO
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

        $this->openPayUBridge->setAuthorizationData(
            $api['environment'],
            $api['client_id'],
            $api['client_secret']
        );
    }

    /**
     * @param Capture $request
     */
    public function execute($request)
    {
        /** @var Capture $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (false == $details['transaction_id']) {
            if (false == $details['success_url'] && $request->getToken()) {
                $details['success_url'] = $request->getToken()->getTargetUrl();
            }
            if (false == $details['abort_url'] && $request->getToken()) {
                $details['abort_url'] = $request->getToken()->getTargetUrl();
            }

            if (false == $details['notification_url'] && $request->getToken() && $this->tokenFactory) {
                $notifyToken = $this->tokenFactory->createNotifyToken(
                    $request->getToken()->getGatewayName(),
                    $request->getToken()->getDetails()
                );

                $details['notification_url'] = $notifyToken->getTargetUrl();
            }

            $this->gateway->execute(new CreateTransaction($details));
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