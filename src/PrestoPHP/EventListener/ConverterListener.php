<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrestoPHP\EventListener;

use PrestoPHP\CallbackResolver;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * Handles converters.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ConverterListener implements EventSubscriberInterface
{
    protected $routes;
    protected $callbackResolver;

    /**
     * Constructor.
     *
     * @param RouteCollection  $routes           A RouteCollection instance
     * @param CallbackResolver $callbackResolver A CallbackResolver instance
     */
    public function __construct(RouteCollection $routes, CallbackResolver $callbackResolver)
    {
        $this->routes = $routes;
        $this->callbackResolver = $callbackResolver;
    }

    /**
     * Handles converters.
     *
     * @param ControllerEvent $event The event to handle
     */
    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        if ($routeName === null) return;

        $route = $this->routes->get($routeName);
        if ($route && $converters = $route->getOption('_converters')) {
            foreach ($converters as $name => $callback) {
                $callback = $this->callbackResolver->resolveCallback($callback);

                $request->attributes->set($name, call_user_func($callback, $request->attributes->get($name), $request));
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
