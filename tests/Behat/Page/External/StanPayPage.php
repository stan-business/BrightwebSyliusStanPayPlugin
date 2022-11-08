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

    public function notify(string $content): void
    {
        $notifyToken = $this->findToken('notify');

        $notifyUrl = $this->payumNotifyPage->getNotifyUrl([
            'gateway' => 'stan_pay',
        ]);

        $payload = sprintf($content, $notifyToken->getHash());
        $this->client->request(
            'POST',
            $notifyUrl,
            [],
            [],
            "",
            $payload
        );
    }

    private function findToken(string $type = 'capture'): TokenInterface
    {
        $foundToken = null;
        /** @var TokenInterface[] $tokens */
        $tokens = $this->securityTokenRepository->findAll();
        foreach ($tokens as $token) {
            if (in_array($token->getHash(), $this->deadTokens)) {
                continue;
            }

            if (false === strpos($token->getTargetUrl(), $type)) {
                continue;
            }

            $foundToken = $token;
        }

        if (null === $foundToken) {
            throw new RuntimeException('Cannot find token, check if you are after proper checkout steps');
        }

        // Sometime the token found is an already consumed one. Here we compare
        // the $foundToken->getAfterUrl() with all tokens to see if the token
        // concerned by the after url is alive, if not we save it to a dead list
        // and retry to found the right token
        if ($type !== 'notify') {
            $relatedToken = null;
            foreach ($tokens as $token) {
                if (false === strpos($foundToken->getAfterUrl(), $token->getHash())) {
                    continue;
                }
                $relatedToken = $token;
            }

            if (null === $relatedToken) {
                $this->deadTokens[] = $foundToken->getHash();
                return $this->findToken($type);
            }
        }

        return $foundToken;
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return 'https://stan-app.fr';
    }
}