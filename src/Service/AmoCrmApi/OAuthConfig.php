<?php

namespace App\Service\AmoCrmApi;

use AmoCRM\OAuth\OAuthConfigInterface;

class OAuthConfig implements OAuthConfigInterface
{
    public function __construct(
        private readonly string $integrationId,
        private readonly string $secretKey,
        private readonly string $redirectDomain,
        private readonly string $baseDomain,
        private readonly string $redirectRoute
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegrationId(): string
    {
        return $this->integrationId;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectDomain(): string
    {
        return sprintf('%s/%s', $this->redirectDomain, $this->redirectRoute);
    }

    public function getBaseDomain(): string
    {
        return $this->baseDomain;
    }
}
