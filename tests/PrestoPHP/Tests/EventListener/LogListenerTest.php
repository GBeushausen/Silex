<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PrestoPHP\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use PrestoPHP\EventListener\LogListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * LogListener.
 *
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
class LogListenerTest extends TestCase
{
    public function testRequestListener()
    {
        $logger = $this->getMockBuilder('Psr\\Log\\LoggerInterface')->getMock();
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::DEBUG, '> GET /foo')
        ;

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new LogListener($logger));

        $kernel = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\HttpKernelInterface')->getMock();

		$dispatcher->dispatch(new RequestEvent($kernel, Request::create('/subrequest'), HttpKernelInterface::SUB_REQUEST), KernelEvents::REQUEST, 'Skip sub requests');

		$dispatcher->dispatch(new RequestEvent($kernel, Request::create('/foo'), HttpKernelInterface::MASTER_REQUEST), KernelEvents::REQUEST, 'Log master requests');
    }

    public function testResponseListener()
    {
        $logger = $this->getMockBuilder('Psr\\Log\\LoggerInterface')->getMock();
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::DEBUG, '< 301')
        ;

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new LogListener($logger));

        $kernel = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\HttpKernelInterface')->getMock();

        $dispatcher->dispatch(new ResponseEvent($kernel, Request::create('/foo'), HttpKernelInterface::SUB_REQUEST, Response::create('subrequest', 200)), KernelEvents::RESPONSE, 'Skip sub requests');

        $dispatcher->dispatch(new ResponseEvent($kernel, Request::create('/foo'), HttpKernelInterface::MASTER_REQUEST, Response::create('bar', 301)), KernelEvents::RESPONSE, 'Log master requests');
    }

    public function testExceptionListener()
    {
        $logger = $this->getMockBuilder('Psr\\Log\\LoggerInterface')->getMock();
        $logger
            ->expects($this->at(0))
            ->method('log')
            ->with(LogLevel::CRITICAL, 'RuntimeException: Fatal error (uncaught exception) at '.__FILE__.' line '.(__LINE__ + 13))
        ;
        $logger
            ->expects($this->at(1))
            ->method('log')
            ->with(LogLevel::ERROR, 'Symfony\Component\HttpKernel\Exception\HttpException: Http error (uncaught exception) at '.__FILE__.' line '.(__LINE__ + 9))
        ;

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new LogListener($logger));

        $kernel = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\HttpKernelInterface')->getMock();

        $dispatcher->dispatch(new ExceptionEvent($kernel, Request::create('/foo'), HttpKernelInterface::SUB_REQUEST, new \RuntimeException('Fatal error')), KernelEvents::EXCEPTION);
        $dispatcher->dispatch(new ExceptionEvent($kernel, Request::create('/foo'), HttpKernelInterface::SUB_REQUEST, new HttpException(400, 'Http error')), KernelEvents::EXCEPTION);
    }
}
