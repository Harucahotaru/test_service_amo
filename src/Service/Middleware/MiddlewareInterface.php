<?php

namespace App\Service\Middleware;

interface MiddlewareInterface
{
    public function setNext(MiddlewareInterface $Middleware): self;
}
