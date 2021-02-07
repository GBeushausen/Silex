<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrestoPHP\Provider\Session;

use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\EventListener\TestSessionListener as BaseTestSessionListener;

/**
 * Simulates sessions for testing purpose.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TestSessionListener extends BaseTestSessionListener
{
    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
        parent::__construct(new PsrContainer($app));
    }

    protected function getSession(): ?SessionInterface {
        if (!isset($this->app['session'])) {
            return null;
        }

        return $this->app['session'];
    }
}
