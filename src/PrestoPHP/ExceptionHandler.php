<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrestoPHP;

use Symfony\Component\Debug\ExceptionHandler as DebugExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Default exception handler.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ExceptionHandler implements EventSubscriberInterface
{
    protected $debug;

    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    public function onPrestoPHPError(GetResponseForExceptionEvent $event)
    {
        $handler = new DebugExceptionHandler($this->debug);

        $exception = $event->getException();
        if (!$exception instanceof FlattenException) {
            $exception = FlattenException::create($exception);
        }

        $response = Response::create($handler->getHtml($exception), $exception->getStatusCode(), $exception->getHeaders())->setCharset(ini_get('default_charset'));

        $event->setResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => ['onPrestoPHPError', -255]];
    }
}