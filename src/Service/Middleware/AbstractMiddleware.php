<?php

namespace App\Service\Middleware;

abstract class AbstractMiddleware implements MiddlewareInterface
{
    protected MiddlewareInterface $next;

    /**
     * {@inheritdoc}
     */
    public function setNext(MiddlewareInterface $Middleware): MiddlewareInterface
    {
        $this->next = $Middleware;

        return $Middleware;
    }
}
