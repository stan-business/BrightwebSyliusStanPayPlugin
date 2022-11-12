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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Request;

use Brightweb\SyliusStanPayPlugin\Api;

use Stan\Model\ApiSettingsRequestBody;

final class StanPayGatewayConfigurationType extends AbstractType
{
    /**
     * @var string
     */
    private $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        var_dump($this->baseUrl);
        $builder
            ->add(
                'environment',
                ChoiceType::class,
                [
                    'label' => 'brightweb.stan_pay_plugin.form.gateway_configuration.environment',
                    'choices' => [
                        'brightweb.stan_pay_plugin.form.gateway_configuration.live' => Api::STAN_MODE_LIVE,
                        'brightweb.stan_pay_plugin.form.gateway_configuration.test' => Api::STAN_MODE_TEST,
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
            )
            ->add(
                'website_url',
                HiddenType::class,
            )
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $gatewayOptions = $event->getData();

                $api = new Api(array(
                    "environment" => Api::STAN_MODE_LIVE,
                    "client_id" => $gatewayOptions["live_api_client_id"],
                    "client_secret" => $gatewayOptions["live_api_secret"]
                ));

                $apiSettings = new ApiSettingsRequestBody();

                $apiSettings->setPaymentWebhookUrl("{$this->baseUrl}/payment/notify/unsafe/stan_pay");

                $api->updateApiSettings($apiSettings);
            });
    }
}
