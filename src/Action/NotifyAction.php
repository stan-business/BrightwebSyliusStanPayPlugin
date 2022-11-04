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
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Sync;

use Brightweb\SyliusStanPayPlugin\Bridge\StanPayBridgeInterface;

// TODO
class NotifyAction implements ActionInterface, ApiAwareInterface
{
    use GatewayAwareTrait;

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
     * @param Notify $request
     */
    public function execute($request)
    {
        // RequestNotSupportedException::assertSupports($this, $request);

        // $this->gateway->execute(new Sync($request->getModel()));

        // throw new HttpResponse('OK', 200);
    }

    public function supports($request)
    {
        return $request instanceof Notify &&
            $request->getModel() instanceof ArrayAccess
        ;
    }
}