<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin\Resolver;

use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;
use Sylius\Component\Payment\Model\PaymentInterface as BasePaymentInterface;

final class DisplayStanPaymentMethodResolver implements PaymentMethodsResolverInterface
{
    private PaymentMethodsResolverInterface $decoratedPaymentMethodsResolver;

    public function __construct(PaymentMethodsResolverInterface $decoratedPaymentMethodsResolver)
    {
        $this->decoratedPaymentMethodsResolver = $decoratedPaymentMethodsResolver;
    }

    public function getSupportedMethods(BasePaymentInterface $subject): array
    {
        $supportedMethods = $this->decoratedPaymentMethodsResolver->getSupportedMethods($subject);

        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        // for PHP < 8.0
        if (!function_exists('str_contains')) {
            function str_contains($haystack, $needle) {
                return $needle !== '' && mb_strpos($haystack, $needle) !== false;
            }
        }

        foreach ($supportedMethods as $index => $method) {
            $gatewayConfig = $method->getGatewayConfig()->getConfig();

            if (isset($gatewayConfig["only_for_stanner"])) {
                if (true === (bool) $gatewayConfig["only_for_stanner"] && ! str_contains($userAgent, "StanApp")) {
                    unset($supportedMethods[$index]);
                    break;
                }
            }
        }

        return $supportedMethods;
    }

    public function supports(BasePaymentInterface $subject): bool
    {
        $this->decoratedPaymentMethodsResolver->supports($subject);
    }
}