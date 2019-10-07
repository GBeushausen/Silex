<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Silex\Provider\Session;

use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;
use Symfony\Component\HttpKernel\EventListener\TestSessionListener as BaseTestSessionListener;

/**
 * Simulates sessions for testing purpose.
 *
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

    protected function getSession()
    {
        if (!isset($this->app['session'])) {
            return;
        }

        return $this->app['session'];
    }
}
