<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Gunnar Beushausen <gunnar@prestophp.com>	
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrestoPHP\Tests;

use PHPUnit\Framework\TestCase;
use PrestoPHP\ServiceControllerResolver;
use PrestoPHP\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Unit tests for ServiceControllerResolver, see ServiceControllerResolverRouterTest for some
 * integration tests.
 */
class ServiceControllerResolverTest extends Testcase
{
    private $app;
    private $mockCallbackResolver;
    private $mockResolver;
    private $resolver;

    public function setup(): void
    {
        $this->mockResolver = $this->getMockBuilder('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockCallbackResolver = $this->getMockBuilder('PrestoPHP\CallbackResolver')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app = new Application();
        $this->resolver = new ServiceControllerResolver($this->mockResolver, $this->mockCallbackResolver);
    }

    public function testShouldResolveServiceController()
    {
        $this->mockCallbackResolver->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->mockCallbackResolver->expects($this->once())
            ->method('convertCallback')
            ->with('some_service:methodName')
            ->will($this->returnValue(['callback']));

        $this->app['some_service'] = function () { return new \stdClass(); };

        $req = Request::create('/');
        $req->attributes->set('_controller', 'some_service:methodName');

        $this->assertEquals(['callback'], $this->resolver->getController($req));
    }

    public function testShouldUnresolvedControllerNames()
    {
        $req = Request::create('/');
        $req->attributes->set('_controller', 'some_class::methodName');

        $this->mockCallbackResolver->expects($this->once())
            ->method('isValid')
            ->with('some_class::methodName')
            ->will($this->returnValue(false));

        $this->mockResolver->expects($this->once())
            ->method('getController')
            ->with($req)
            ->will($this->returnValue(123));

        $this->assertEquals(123, $this->resolver->getController($req));
    }
}
