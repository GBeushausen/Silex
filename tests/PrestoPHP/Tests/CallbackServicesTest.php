<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Gunnar Beushausen <gunnar@prestophp.com>	
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PrestoPHP\Tests;

use PHPUnit\Framework\TestCase;
use PrestoPHP\Application;
use Symfony\Component\HttpFoundation\Request;
use PrestoPHP\Provider\ServiceControllerServiceProvider;

/**
 * Callback as services test cases.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class CallbackServicesTest extends TestCase
{
    public $called = [];

    public function testCallbacksAsServices()
    {
        $app = new Application();
        $app->register(new ServiceControllerServiceProvider());

        $app['service'] = function () {
            return new CallbackServicesTest();
        };

        $app->before('service:beforeApp');
        $app->after('service:afterApp');
        $app->finish('service:finishApp');
        $app->error('service:error');
        $app->on('kernel.request', 'service:onRequest');

        $app
            ->match('/', 'service:controller')
            ->convert('foo', 'service:convert')
            ->before('service:before')
            ->after('service:after')
        ;

        $request = Request::create('/');
        $response = $app->handle($request);
        $app->terminate($request, $response);

        $this->assertEquals([
            'BEFORE APP',
            'ON REQUEST',
            'BEFORE',
            'CONVERT',
            'ERROR',
            'AFTER',
            'AFTER APP',
            'FINISH APP',
        ], $app['service']->called);
    }

    public function controller(Application $app)
    {
        $app->abort(404);
    }

    public function before()
    {
        $this->called[] = 'BEFORE';
    }

    public function after()
    {
        $this->called[] = 'AFTER';
    }

    public function beforeApp()
    {
        $this->called[] = 'BEFORE APP';
    }

    public function afterApp()
    {
        $this->called[] = 'AFTER APP';
    }

    public function finishApp()
    {
        $this->called[] = 'FINISH APP';
    }

    public function error()
    {
        $this->called[] = 'ERROR';
    }

    public function convert()
    {
        $this->called[] = 'CONVERT';
    }

    public function onRequest()
    {
        $this->called[] = 'ON REQUEST';
    }
}
