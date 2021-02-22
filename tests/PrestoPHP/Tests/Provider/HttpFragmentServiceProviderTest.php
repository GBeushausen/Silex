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
use PrestoPHP\Provider\HttpFragmentServiceProvider;
use PrestoPHP\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class HttpFragmentServiceProviderTest extends TestCase
{
    public function testRenderFunction()
    {
        $app = new Application();
        unset($app['exception_handler']);

        $app->register(new HttpFragmentServiceProvider());
        $app->register(new HttpCacheServiceProvider(), ['http_cache.cache_dir' => sys_get_temp_dir()]);
        $app->register(new TwigServiceProvider(), [
            'twig.templates' => [
                'hello' => '{{ render("/foo") }}{{ render_esi("/foo") }}{{ render_hinclude("/foo") }}',
                'foo' => 'foo',
            ],
        ]);

        $app->get('/hello', function () use ($app) {
            return $app['twig']->render('hello');
        });

        $app->get('/foo', function () use ($app) {
            return $app['twig']->render('foo');
        });

        $response = $app['http_cache']->handle(Request::create('/hello'));

        $this->assertEquals('foofoo<hx:include src="/foo"></hx:include>', $response->getContent());
    }
}
