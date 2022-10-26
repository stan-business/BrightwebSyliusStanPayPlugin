<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanPayPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

use Brightweb\SyliusStanPayPlugin\ApiClient\StanPayApiClientInterface;

final class StanPayGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'environment',
                ChoiceType::class,
                [
                    'label' => 'brightweb.stan_pay_plugin.form.gateway_configuration.environment',
                    'choices' => [
                        'brightweb.stan_pay_plugin.form.gateway_configuration.live' => StanPayApiClientInterface::STAN_MODE_LIVE,
                        'brightweb.stan_pay_plugin.form.gateway_configuration.test' => StanPayApiClientInterface::STAN_MODE_TEST,
                    ],
                ]
            )
            ->add(
                'live_api_client_id',
                TextType::class,
                [
                    'label' => 'brightweb.stan_pay_plugin.form.gateway_configuration.live_api_client_id',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'brightweb.stan_pay_plugin.form.validator.api_client_id.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'test_api_client_id',
                TextType::class,
                [
                    'label' => 'brightweb.stan_pay_plugin.form.gateway_configuration.test_api_client_id',
                ]
            )
            ->add(
                'live_api_secret',
                TextType::class,
                [
                    'label' => 'brightweb.stan_pay_plugin.form.gateway_configuration.live_api_client_secret',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'brightweb.stan_pay_plugin.form.validator.api_client_secret.not_blank',
                                'groups' => ['sylius'],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'test_api_secret',
                TextType::class,
                [
                    'label' => 'brightweb.stan_pay_plugin.form.gateway_configuration.test_api_client_secret',
                ]
            )
            ->add(
                'only_for_stanner',
                CheckboxType::class,
                [
                    'label' => 'brightweb.stan_pay_plugin.form.gateway_configuration.only_for_stanner',
                    'help' => 'brightweb.stan_pay_plugin.form.gateway_configuration.only_for_stanner_tip',
                ]
            );
    }
}
