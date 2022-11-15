<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Tests\Brightweb\SyliusStanPayPlugin\Behat\Mocker;

use Brightweb\SyliusStanPayPlugin\Action\Api\PreparePaymentAction;
use Brightweb\SyliusStanPayPlugin\Request\Api\PreparePayment;
use Brightweb\SyliusStanPayPlugin\Action\Api\GetPaymentAction;
use Brightweb\SyliusStanPayPlugin\Request\Api\GetPayment;
use Brightweb\SyliusStanPayPlugin\Api;

use Sylius\Behat\Service\Mocker\MockerInterface;

final class StanPayApiMocker
{
    /** @var MockerInterface */
    private $mocker;

    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    public function mockSuccessfulPayment(
        callable $action
    ): void {
        $this->mockGetPayment($action, "payment_success");
    }

    public function mockPreparePayment(callable $action): void
    {
        $mock = $this->mocker->mockService(
            'tests.brightweb.stan_pay_plugin.behat.mocker.action.prepare_payment',
            PreparePaymentAction::class
        );

        $mock
            ->shouldReceive('setApi')
            ->once();
        $mock
            ->shouldReceive('setGateway')
            ->once();

        $mock
            ->shouldReceive('supports')
            ->andReturnUsing(function ($request) {
                toto();
                return $request instanceof PreparePayment;
            });

        $mock
            ->shouldReceive('execute')
            ->once()
            ->andReturnUsing(function (PreparePayment $request) {
                $details = ArrayObject::ensureArrayObject($request->getModel());

                $details->replace([
                    'stan_payment_id' => 'stan_payment',
                    'payment_url' => 'mock'
                ]);
            });

        $action();

        $this->mocker->unmockAll();
    }

    public function mockGetPayment(callable $action, string $paymentStatus): void
    {
        $mock = $this->mocker->mockService(
            'tests.brightweb.stan_pay_plugin.behat.mocker.action.get_payment',
            GetPaymentAction::class
        );

        $mock
            ->shouldReceive('setApi')
            ->once();
        $mock
            ->shouldReceive('setGateway')
            ->once();

        $mock
            ->shouldReceive('supports')
            ->andReturnUsing(function ($request) {
                return $request instanceof GetPayment;
            });

        $mock
            ->shouldReceive('execute')
            ->once()
            ->andReturnUsing(function (GetPayment $request) {
                $details = ArrayObject::ensureArrayObject($request->getModel());

                $details->replace([
                    'stan_payment_status' => $paymentStatus,
                ]);
            });

        $action();

        $this->mocker->unmockAll();
    }
}