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
use Symfony\Component\HttpKernel\EventListener\SessionListener as BaseSessionListener;

/**
 * Sets the session in the request.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SessionListener extends BaseSessionListener
{
    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    protected function getSession()
    {
        if (!isset($this->app['session'])) {
            return;
        }

        return $this->app['session'];
    }
}
