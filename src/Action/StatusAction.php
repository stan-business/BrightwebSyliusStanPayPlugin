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
use Payum\Core\Request\GetStatusInterface;

use Brightweb\SyliusStanPayPlugin\Api;

class StatusAction implements ActionInterface
{
    /**
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (! isset($details['stan_payment_id']) || ! strlen($details['stan_payment_id'])) {
            $request->markNew();

            return;
        }

        if (! isset($details['stan_payment_status'])) {
            $request->markNew();

            return;
        }

        switch ($details['stan_payment_status']) {
            case Api::PAYMENT_FAILURE:
                $request->markFailed();
                break;
            case Api::PAYMENT_CANCELLED:
                $request->markCanceled();
                break;
            case Api::PAYMENT_PENDING:
            case Api::PAYMENT_HOLDING:
            case Api::PAYMENT_PREPARED:
                $request->markPending();
                break;
            case Api::PAYMENT_SUCCESS:
                $request->markCaptured();
                break;
            default:
                $request->markUnknown();
                break;
        }
    }

    public function supports($request)
    {
        return $request instanceof GetStatusInterface &&
            $request->getModel() instanceof ArrayAccess
        ;
    }
}