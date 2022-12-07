<?php

namespace App\Service\Redis;

use Predis\Client;

/**
 * Фабрика редис клиентов.
 */
class RedisFactory
{
    public function getClient(string $host, string $port): Client
    {
        return new Client(
            [
                'host' => $host,
                'port' => $port,
            ]
        );
    }
}
