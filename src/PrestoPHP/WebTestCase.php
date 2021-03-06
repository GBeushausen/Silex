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

use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * WebTestCase is the base class for functional tests.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 */
abstract class WebTestCase extends TestCase
{
    /**
     * HttpKernelInterface instance.
     *
     * @var HttpKernelInterface
     */
    protected $app;

    /**
     * PHPUnit setUp for setting up the application.
     *
     * Note: Child classes that define a setUp method must call
     * parent::setUp().
     */
    protected function setUp(): void
    {
        $this->app = $this->createApplication();
    }

    /**
     * Creates the application.
     *
     * @return HttpKernelInterface
     */
    abstract public function createApplication();

    /**
     * Creates a Client.
     *
     * @param array $server Server parameters
     *
     * @return AbstractBrowser A Client instance
     */
    public function createClient(array $server = [])
    {
		if (class_exists(Client::class)) {
			return new Client($this->app, $server);
		}

		return new HttpKernelBrowser($this->app, $server);
    }
}
