<?php

namespace Horde\Kolab\Format\Test\Stub;

class Log
{
    public $log = array();

    public function debug($message)
    {
        $this->log[] = $message;
    }
}
