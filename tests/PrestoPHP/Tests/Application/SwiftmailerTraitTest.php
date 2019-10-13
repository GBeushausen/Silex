<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PrestoPHP\Tests\Application;

use PHPUnit\Framework\TestCase;
use PrestoPHP\Provider\SwiftmailerServiceProvider;

/**
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SwiftmailerTraitTest extends TestCase
{
    public function testMail()
    {
        $app = $this->createApplication();

        $message = $this->getMockBuilder('Swift_Message')->disableOriginalConstructor()->getMock();
        $app['mailer'] = $mailer = $this->getMockBuilder('Swift_Mailer')->disableOriginalConstructor()->getMock();
        $mailer->expects($this->once())
               ->method('send')
               ->with($message)
        ;

        $app->mail($message);
    }

    public function createApplication()
    {
        $app = new SwiftmailerApplication();
        $app->register(new SwiftmailerServiceProvider());

        return $app;
    }
}
