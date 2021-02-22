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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * UrlGeneratorTrait test cases.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class UrlGeneratorTraitTest extends TestCase
{
    public function testUrl()
    {
        $app = new UrlGeneratorApplication();
        $app['url_generator'] = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')->disableOriginalConstructor()->getMock();
        $app['url_generator']->expects($this->once())->method('generate')->with('foo', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $app->url('foo');
    }

    public function testPath()
    {
        $app = new UrlGeneratorApplication();
        $app['url_generator'] = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')->disableOriginalConstructor()->getMock();
        $app['url_generator']->expects($this->once())->method('generate')->with('foo', [], UrlGeneratorInterface::ABSOLUTE_PATH);
        $app->path('foo');
    }
}
