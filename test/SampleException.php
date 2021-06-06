<?php
declare(strict_types=1);

namespace knotlib\exceptionhandler\file\test;

use knotlib\exception\KnotPhpException;

final class SampleException extends KnotPhpException
{
    public function __construct($message = "sample exception")
    {
        parent::__construct($message, 100, new \Exception("this is cause"));
    }

}