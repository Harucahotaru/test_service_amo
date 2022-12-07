<?php

namespace App\Service\AmoCrmApi;

use AmoCRM\Client\AmoCRMApiClientFactory;

class AmoCrmApiClientFactoryAdapter extends AmoCRMApiClientFactory
{
    public function getConfig(): OAuthConfig
    {
        return $this->oAuthConfig;
    }

    public function getService(): OAuthService
    {
        return $this->oAuthService;
    }
}
