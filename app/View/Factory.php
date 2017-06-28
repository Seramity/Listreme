<?php

namespace App\View;

/**
 * Class Factory
 *
 * Creates a new view factory with Twig.
 *
 * @package App\View
 */
class Factory
{
    /**
     * @var string
     */
    protected $view;

    /**
     * Creates an new instance of Twig.
     *
     * @param object|NULL $container
     *
     * @return \Slim\Views\Twig
     */
    public static function getEngine($container = NULL)
    {
        return new \Slim\Views\Twig(__DIR__ . '/../../resources/views', [
            'debug' => $container['settings']['app']['debug'],
            'cache' => ($container['settings']['app']['cache'] ? __DIR__ . '/../../resources/cache/views' : FALSE)
        ]);
    }

    /**
     * Parses the view and data into HTML and returns it.
     *
     * @param string $view
     * @param array $data
     *
     * @return Factory
     */
    public function make($view, $data = [])
    {
        $this->view = static::getEngine()->fetch($view, $data);

        return $this;
    }

    /**
     * Renders the view.
     *
     * @return string
     */
    public function render()
    {
        return $this->view;
    }
}