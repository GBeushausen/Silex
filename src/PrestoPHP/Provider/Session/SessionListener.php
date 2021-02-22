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

namespace PrestoPHP\Provider\Session;

use Pimple\Container;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\EventListener\SessionListener as BaseSessionListener;

/**
 * Sets the session in the request.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SessionListener extends BaseSessionListener
{
	private Container $app;

	public function __construct(Container $app)
	{
		$this->app = $app;
	}

	protected function getSession(): ?SessionInterface
	{
		if (!isset($this->app['session'])) {
			return null;
		}

		return $this->app['session'];
	}
}
