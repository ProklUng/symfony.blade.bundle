<?php

namespace Prokl\SymfonyBladeBundle\Services\Providers;

use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory;

/**
 * Class BladeAgnosticProvider
 * @package Prokl\SymfonyBladeBundle\Services\Providers
 */
class BladeAgnosticProvider extends BladeBaseProvider
{
    /**
     * Path to a folder view common view can be stored.
     *
     * @var string
     */
    protected static $baseViewPath;

    /**
     * Local path to blade cache storage.
     *
     * @var string
     */
    protected static $cachePath;

    /**
     * View factory.
     *
     * @var Factory $viewFactory
     */
    protected static $viewFactory;

    /**
     * Service container factory.
     *
     * @var Container $container
     */
    protected static $container;
}
