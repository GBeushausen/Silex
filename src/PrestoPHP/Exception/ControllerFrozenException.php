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

namespace PrestoPHP\Exception;

/**
 * Exception, is thrown when a frozen controller is modified.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 */
class ControllerFrozenException extends \RuntimeException
{
}
