<?php
declare(strict_types=1);

namespace KnotLib\ExceptionHandler\File\Test;

use PHPUnit\Framework\TestCase;
use Stk2k\File\File;

use KnotLib\ExceptionHandler\File\FileExceptionHandler;
use KnotLib\Exception\Runtime\HttpStatusException;
use KnotLib\ExceptionHandler\Text\TextDebugtraceRenderer;

class FileExceptionHandlerTest extends TestCase
{
    public function testHandleException()
    {
        //======================================
        // exception_1.txt
        //======================================
        $file = new File("exception_1.txt", __DIR__ . "/tmp");

        $handler = new FileExceptionHandler($file, new TextDebugtraceRenderer);

        $e = new \Exception("test");

        $handler->handleException($e);

        $output = explode(PHP_EOL, $file->getContents());

        $this->assertEquals('=============================================================', $output[0] ?? null);
        $this->assertEquals('Exception stack trace', $output[1] ?? null);
        $this->assertEquals('=============================================================', $output[2] ?? null);
        $this->assertEquals('', $output[3] ?? null);
        $this->assertEquals('* Exception Stack *', $output[4] ?? null);
        $this->assertEquals('-------------------------------------------------------------', $output[5] ?? null);
        $this->assertEquals('[1]Exception', $output[6] ?? null);
        $this->assertEquals('   test', $output[8] ?? null);

        //======================================
        // exception_2.txt
        //======================================
        $file = new File("exception_2.txt", __DIR__ . "/tmp");

        $handler = new FileExceptionHandler($file, new TextDebugtraceRenderer);

        $e = new SampleException();

        $handler->handleException($e);

        $output = explode(PHP_EOL, $file->getContents());

        $this->assertEquals('=============================================================', $output[0] ?? null);
        $this->assertEquals('Exception stack trace', $output[1] ?? null);
        $this->assertEquals('=============================================================', $output[2] ?? null);
        $this->assertEquals('', $output[3] ?? null);
        $this->assertEquals('* Exception Stack *', $output[4] ?? null);
        $this->assertEquals('-------------------------------------------------------------', $output[5] ?? null);
        $this->assertEquals('[1]KnotLib\ExceptionHandler\File\Test\SampleException', $output[6] ?? null);
        $this->assertEquals('   sample exception', $output[8] ?? null);
        $this->assertEquals('[2]Exception', $output[10] ?? null);
        $this->assertEquals('   this is cause', $output[12] ?? null);
        $this->assertEquals('* Call Stack *', $output[14] ?? null);
        $this->assertEquals('-------------------------------------------------------------', $output[15] ?? null);
        $this->assertEquals('[0]KnotLib\Exception\KnotPhpException->__construct()', $output[16] ?? null);
        $this->assertEquals('[1]KnotLib\ExceptionHandler\File\Test\SampleException->__construct()', $output[19] ?? null);
    }
}