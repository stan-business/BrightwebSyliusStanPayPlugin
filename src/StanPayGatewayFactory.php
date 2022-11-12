<?php

declare(strict_types=1);

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

namespace Brightweb\SyliusStanPayPlugin;

use Brightweb\SyliusStanPayPlugin\Action\Api\CreateCustomerAction;
use Brightweb\SyliusStanPayPlugin\Action\Api\GetPaymentAction;
use Brightweb\SyliusStanPayPlugin\Action\Api\PreparePaymentAction;
use Brightweb\SyliusStanPayPlugin\Action\CaptureAction;
use Brightweb\SyliusStanPayPlugin\Action\ConvertPaymentAction;
use Brightweb\SyliusStanPayPlugin\Action\NotifyAction;
use Brightweb\SyliusStanPayPlugin\Action\NotifyNullAction;
use Brightweb\SyliusStanPayPlugin\Action\StatusAction;
use Brightweb\SyliusStanPayPlugin\Action\SyncAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class StanPayGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'stan_pay',
            'payum.factory_title' => 'Stan Pay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.notify_null' => new NotifyNullAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.sync' => new SyncAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),

            'payum.action.api.create_transaction' => new PreparePaymentAction(),
            'payum.action.api.get_transaction_data' => new GetPaymentAction(),
            'payum.action.api.create_customer' => new CreateCustomerAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => Api::STAN_MODE_TEST,
                'client_id' => '',
                'client_secret' => '',
                'client_test_id' => '',
                'client_test_secret' => '',
                'only_for_stanner' => '',
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = ['environment', 'live_api_client_id', 'live_api_secret'];

            $config['payum.api'] = function (ArrayObject $config): Api {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api([
                    'environment' => $config['environment'],
                    'client_id' => $config['live_api_client_id'],
                    'client_secret' => $config['live_api_secret'],
                    'client_test_id' => $config['test_api_client_id'],
                    'client_test_secret' => $config['test_api_secret'],
                    'only_for_stanner' => $config['only_for_stanner'],
                ]);
            };
        }
    }
}
