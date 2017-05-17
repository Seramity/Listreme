<?php

namespace App\Middleware;

/**
 * Class Middleware
 *
 * Base middleware class for other middleware classes to extend off of.
 *
 * @package App\Middleware
 */
class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}