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

namespace PrestoPHP\Tests\Application;

use PHPUnit\Framework\TestCase;
use PrestoPHP\Provider\MonologServiceProvider;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

/**
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class MonologTraitTest extends TestCase
{
    public function testLog()
    {
        $app = $this->createApplication();

        $app->log('Foo');
        $app->log('Bar', [], Logger::DEBUG);
        $this->assertTrue($app['monolog.handler']->hasInfo('Foo'));
        $this->assertTrue($app['monolog.handler']->hasDebug('Bar'));
    }

    public function createApplication()
    {
        $app = new MonologApplication();
        $app->register(new MonologServiceProvider(), [
            'monolog.handler' => function () use ($app) {
                return new TestHandler($app['monolog.level']);
            },
            'monolog.logfile' => 'php://memory',
        ]);

        return $app;
    }
}
