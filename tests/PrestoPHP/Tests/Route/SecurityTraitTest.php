<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PrestoPHP\Tests\Route;

use PHPUnit\Framework\TestCase;
use PrestoPHP\Application;
use PrestoPHP\Provider\SecurityServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * SecurityTrait test cases.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SecurityTraitTest extends TestCase
{
    public function testSecureWithNoAuthenticatedUser()
    {
        $app = $this->createApplication();

        $app->get('/', function () { return 'foo'; })
            ->secure('ROLE_ADMIN')
        ;

        $request = Request::create('/');
        $response = $app->handle($request);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testSecureWithAuthorizedRoles()
    {
        $app = $this->createApplication();

        $app->get('/', function () { return 'foo'; })
            ->secure('ROLE_ADMIN')
        ;

        $request = Request::create('/');
        $request->headers->set('PHP_AUTH_USER', 'fabien');
        $request->headers->set('PHP_AUTH_PW', 'foo');
        $response = $app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSecureWithUnauthorizedRoles()
    {
        $app = $this->createApplication();

        $app->get('/', function () { return 'foo'; })
            ->secure('ROLE_SUPER_ADMIN')
        ;

        $request = Request::create('/');
        $request->headers->set('PHP_AUTH_USER', 'fabien');
        $request->headers->set('PHP_AUTH_PW', 'foo');
        $response = $app->handle($request);
        $this->assertEquals(403, $response->getStatusCode());
    }

    private function createApplication()
    {
        $app = new Application();
        $app['route_class'] = 'PrestoPHP\Tests\Route\SecurityRoute';
        $app->register(new SecurityServiceProvider(), [
            'security.firewalls' => [
                'default' => [
                    'http' => true,
                    'users' => [
                        'fabien' => ['ROLE_ADMIN', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                    ],
                ],
            ],
        ]);

        return $app;
    }
}
