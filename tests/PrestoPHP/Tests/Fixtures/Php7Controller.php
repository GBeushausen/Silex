<?php

/*
 * This file is part of the PrestoPHP framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PrestoPHP\Tests\Fixtures;

use PrestoPHP\Application;

class Php7Controller
{
    public function typehintedAction(Application $application, string $name)
    {
        return 'Hello '.$application->escape($name).' in '.get_class($application);
    }
}
