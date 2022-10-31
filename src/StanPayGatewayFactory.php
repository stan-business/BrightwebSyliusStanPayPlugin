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
                'only_for_stanner' => '',
            ];
            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = ['environment', 'client_id', 'client_secret'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api([
                    'environment' => $config['environment'],
                    'client_id' => $config['client_id'],
                    'client_secret' => $config['client_secret'],
                    'only_for_stanner' => $config['only_for_stanner'],
                ]);
            };
        }
    }
}