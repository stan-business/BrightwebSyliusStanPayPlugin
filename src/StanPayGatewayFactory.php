<?php

namespace Brightweb\SyliusStanPayPlugin;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\GatewayFactory;

use Brightweb\SyliusStanPayPlugin\Bridge\StanPayBridgeInterface;

class StanPayGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'stan_pay',
            'payum.factory_title' => 'Stan Pay',
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => StanPayBridgeInterface::STAN_MODE_TEST,
                'client_id' => '',
                'client_secret' => '',
                'client_test_id' => '',
                'client_test_secret' => '',
                'only_for_stanner' => '',
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = ['environment', 'live_api_client_id', 'live_api_secret'];

            $config['payum.api'] = static function (ArrayObject $config): array {
                $config->validateNotEmpty($config['payum.required_options']);

                return [
                    'environment' => $config['environment'],
                    'client_id' => $config['live_api_client_id'],
                    'client_secret' => $config['live_api_secret'],
                    'client_test_id' => $config['test_api_client_id'],
                    'client_test_secret' => $config['test_api_secret'],
                    'only_for_stanner' => $config['only_for_stanner'],
                ];
            };
        }
    }
}