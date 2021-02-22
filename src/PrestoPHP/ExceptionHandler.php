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

namespace PrestoPHP;

use Symfony\Component\Debug\ExceptionHandler as DebugExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
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

    public function onPrestoPHPError(ExceptionEvent $event)
    {
        $handler = new DebugExceptionHandler($this->debug);

        $throwable = $this->getThrowable($event);
		if (!$throwable instanceof FlattenException) {
			$throwable = $this->flattenException($throwable);
		}

		$response = new Response($handler->getHtml($throwable), $throwable->getStatusCode(), $throwable->getHeaders());
		$response->setCharset(ini_get('default_charset'));

        $event->setResponse($response);
    }

	/**
	 * @param ExceptionEvent $event
	 *
	 * @return \Throwable
	 */
	protected function getThrowable(ExceptionEvent $event): \Throwable
	{
		if (method_exists($event, 'getThrowable')) {
			return $event->getThrowable();
		}

		return $event->getException();
	}

	/**
	 * @param \Exception|\Throwable $exception
	 *
	 * @return FlattenException
	 */
	protected function flattenException($exception): FlattenException
	{
		if ($exception instanceof \Exception) {
			return FlattenException::create($exception);
		}

		return FlattenException::createFromThrowable($exception);
	}

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => ['onPrestoPHPError', -255]];
    }
}
