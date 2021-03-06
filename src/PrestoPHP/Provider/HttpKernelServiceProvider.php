<?php

namespace PrestoPHP\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use PrestoPHP\Api\EventListenerProviderInterface;
use PrestoPHP\AppArgumentValueResolver;
use PrestoPHP\CallbackResolver;
use PrestoPHP\EventListener\ConverterListener;
use PrestoPHP\EventListener\MiddlewareListener;
use PrestoPHP\EventListener\StringToResponseListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\WebLink\EventListener\AddLinkHeaderListener;
use Symfony\Component\WebLink\HttpHeaderSerializer;
use function foo\func;

class HttpKernelServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['resolver'] = function ($app) {
            return new ControllerResolver($app['logger']);
        };

        $app['argument_metadata_factory'] = function ($app) {
            return new ArgumentMetadataFactory();
        };
        $app['argument_value_resolvers'] = function ($app) {
            return array_merge([new AppArgumentValueResolver($app)], ArgumentResolver::getDefaultArgumentValueResolvers());
        };

        $app['argument_resolver'] = function ($app) {
            return new ArgumentResolver($app['argument_metadata_factory'], $app['argument_value_resolvers']);
        };

        $app['kernel'] = function ($app) {
            return new HttpKernel($app['dispatcher'], $app['resolver'], $app['request_stack'], $app['argument_resolver']);
        };

        $app['request_stack'] = function () {
            return new RequestStack();
        };

        $app['url_helper'] = function ($app) {
            return new UrlHelper($app['request_stack']);
        };

        $app['dispatcher'] = function () {
            return new EventDispatcher();
        };

        $app['callback_resolver'] = function ($app) {
            return new CallbackResolver($app);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber(new ResponseListener($app['charset']));
        $dispatcher->addSubscriber(new MiddlewareListener($app));
        $dispatcher->addSubscriber(new ConverterListener($app['routes'], $app['callback_resolver']));
        $dispatcher->addSubscriber(new StringToResponseListener());

        if (class_exists(HttpHeaderSerializer::class)) {
            $dispatcher->addSubscriber(new AddLinkHeaderListener());
        }
    }
}
