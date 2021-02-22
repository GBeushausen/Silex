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

namespace PrestoPHP\Tests\Provider;

use PHPUnit\Framework\TestCase;
use PrestoPHP\Application;
use PrestoPHP\Provider\HttpCacheServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * HttpCacheProvider test cases.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 */
class HttpCacheServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $app = new Application();

        $app->register(new HttpCacheServiceProvider(), [
            'http_cache.cache_dir' => sys_get_temp_dir().'/PrestoPHP_http_cache_'.uniqid(),
        ]);

        $this->assertInstanceOf('PrestoPHP\Provider\HttpCache\HttpCache', $app['http_cache']);

        return $app;
    }

    /**
     * @depends testRegister
     */
    public function testRunCallsShutdown($app)
    {
        $finished = false;

        $app->finish(function () use (&$finished) {
            $finished = true;
        });

        $app->get('/', function () use ($app) {
            return new UnsendableResponse('will do something after finish');
        });

        $request = Request::create('/');
        $app['http_cache']->run($request);

        $this->assertTrue($finished);
    }

    public function testDebugDefaultsToThatOfApp()
    {
        $app = new Application();

        $app->register(new HttpCacheServiceProvider(), [
            'http_cache.cache_dir' => sys_get_temp_dir().'/PrestoPHP_http_cache_'.uniqid(),
        ]);

        $app['debug'] = true;
        $app['http_cache'];
        $this->assertTrue($app['http_cache.options']['debug']);
    }
}

class UnsendableResponse extends Response
{
    public function send()
    {
        // do nothing
    }
}
