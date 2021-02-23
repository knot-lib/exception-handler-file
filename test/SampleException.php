<?php
declare(strict_types=1);

namespace KnotLib\ExceptionHandler\File\Test;

use KnotLib\Exception\KnotPhpException;
use Throwable;

final class SampleException extends KnotPhpException
{
    public function __construct($message = "sample exception")
    {
        parent::__construct($message, 100, new \Exception("this is cause"));
    }

}