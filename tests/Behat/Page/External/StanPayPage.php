<?php

declare(strict_types=1);

namespace Tests\Brightweb\SyliusStanPayPlugin\Behat\Page\External;

use ArrayAccess;
use Behat\Mink\Exception\DriverException;
use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use Payum\Core\Security\TokenInterface;
use RuntimeException;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

use Tests\Brightweb\SyliusStanPayPlugin\Behat\Page\Shop\PayumNotifyPageInterface;

final class StanPayPage extends Page implements StanPayPageInterface
{
    /** @var RepositoryInterface */
    private $securityTokenRepository;

    /** @var HttpKernelBrowser */
    private $client;

    /** @var PayumNotifyPageInterface */
    private $payumNotifyPage;

    /** @var string[] */
    private $deadTokens = [];

    /**
     * @param array|ArrayAccess $minkParameters
     */
    public function __construct(
        Session $session,
        $minkParameters,
        RepositoryInterface $securityTokenRepository,
        HttpKernelBrowser $client,
        PayumNotifyPageInterface $payumNotifyPage
    ) {
        parent::__construct($session, $minkParameters);

        $this->securityTokenRepository = $securityTokenRepository;
        $this->client = $client;
        $this->payumNotifyPage = $payumNotifyPage;
    }

    public function pay(): void
    {
        $this->getDriver()->visit($this->findToken()->getTargetUrl());
    }

    private function findToken(string $name = 'capture'): TokenInterface
    {
        $tokens = $this->securityTokenRepository->findAll();

        foreach ($tokens as $token) {
            if (strpos($token->getTargetUrl(), $name)) {
                return $token;
            }
        }

        throw new \RuntimeException(sprintf('Cannot find "%s" token, check if you are after proper checkout steps', $name));
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return 'https://api.tan-app.fr';
    }
}