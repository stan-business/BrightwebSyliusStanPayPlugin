<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
 */

declare(strict_types=1);

namespace Tests\Brightweb\SyliusStanPayPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;

final class StanPayContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var PaymentMethodRepositoryInterface */
    private $paymentMethodRepository;

    /** @var ExampleFactoryInterface */
    private $paymentMethodExampleFactory;

    /** @var EntityManagerInterface */
    private $paymentMethodManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ExampleFactoryInterface $paymentMethodExampleFactory,
        EntityManagerInterface $paymentMethodManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodExampleFactory = $paymentMethodExampleFactory;
        $this->paymentMethodManager = $paymentMethodManager;
    }

    /**
     * @Given the store has a Stan Pay payment method :paymentMethodName with a code :paymentMethodCode
     */
    public function theStoreHasAStanPayPaymentMethodWithACode(
        string $paymentMethodName,
        string $paymentMethodCode
    ): void {
        $paymentMethod = $this->createPaymentMethod($paymentMethodName, $paymentMethodCode, 'Stan Pay', 'Pay with Stan Pay', true, 0);
        $paymentMethod->getGatewayConfig()->setConfig(
            [
                'environment' => 'test',
                'client_id' => 'client id',
                'client_secret' => 'client secret',
                'client_test_id' => 'client test id',
                'client_test_secret' => 'client test secret',
                'only_for_stanner' => false,
                'payum.http_client' => '@sylius.payum.http_client',
            ]
        );
        $this->paymentMethodManager->flush();
    }

    private function createPaymentMethod(
        string $name,
        string $code,
        string $gatewayFactory,
        string $description = '',
        bool $addForCurrentChannel = true,
        ?int $position = null
    ): PaymentMethodInterface {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $this->paymentMethodExampleFactory->create(
            [
                'name' => ucfirst($name),
                'code' => $code,
                'description' => $description,
                'gatewayName' => $gatewayFactory,
                'gatewayFactory' => $gatewayFactory,
                'enabled' => true,
                'channels' => ($addForCurrentChannel && $this->sharedStorage->has('channel'))
                    ? [$this->sharedStorage->get('channel')] : [],
            ]
        );

        if (null !== $position) {
            $paymentMethod->setPosition((int) $position);
        }

        $this->sharedStorage->set('payment_method', $paymentMethod);
        $this->paymentMethodRepository->add($paymentMethod);

        return $paymentMethod;
    }
}