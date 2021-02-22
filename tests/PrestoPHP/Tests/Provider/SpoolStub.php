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

namespace PrestoPHP\Tests\Provider;

class SpoolStub implements \Swift_Spool
{
    private $messages = [];
    public $hasFlushed = false;

    public function getMessages()
    {
        return $this->messages;
    }

    public function start()
    {
    }

    public function stop()
    {
    }

    public function isStarted()
    {
        return count($this->messages) > 0;
    }

    public function queueMessage($message)
    {
        $this->messages[] = clone $message;
    }

    public function flushQueue(\Swift_Transport $transport, &$failedRecipients = null)
    {
        $this->hasFlushed = true;
        $this->messages = [];
    }
}
