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

namespace PrestoPHP\Route;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Security trait.
 *
 * @author Gunnar Beushausen <gunnar@prestophp.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */
trait SecurityTrait
{
    public function secure($roles)
    {
        $this->before(function ($request, $app) use ($roles) {
            if (!$app['security.authorization_checker']->isGranted($roles)) {
                throw new AccessDeniedException();
            }
        });
    }
}
