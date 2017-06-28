<?php

namespace App\View;

/**
 * Class DebugExtension
 *
 * Enables the Symfony Vardumper dump() function in Twig.
 *
 * @package App\View
 */
class DebugExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('dump', [$this, 'dump'])
        ];
    }

    public function dump($var)
    {
        return dump($var);
    }
}