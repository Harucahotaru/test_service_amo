<?php

namespace App\Service\AmoCrmApi;

use AmoCRM\OAuth\OAuthServiceInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Predis\Client;

class OAuthService implements OAuthServiceInterface
{
    /**
     * @var string
     */
    private const TOKEN_KEY = 'token_data';

    /**
     * @var int
     */
    private const EXPIRE_TTL = 604800;

    public function __construct(
        private readonly Client $Client
    ) {
    }

    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {
        $this->Client->setex(
            self::TOKEN_KEY,
            self::EXPIRE_TTL,
            json_encode($accessToken)
        );
    }

    /**
     * @return ?AccessTokenInterface
     */
    public function getOAuthToken(): ?AccessTokenInterface
    {
        $token = $this->Client->get(self::TOKEN_KEY);
        if (!$token) {
            return null;
        }

        return new AccessToken(json_decode($token, JSON_OBJECT_AS_ARRAY));
    }
}
