<?php
declare(strict_types=1);

namespace knotlib\exceptionhandler\file\test;

use Exception;
use PHPUnit\Framework\TestCase;
use Stk2k\File\File;
use Stk2k\File\Exception\FileInputException;
use Stk2k\File\Exception\FileOutputException;
use Stk2k\File\Exception\MakeDirectoryException;

use knotlib\exceptionhandler\file\FileExceptionHandler;
use knotlib\exceptionhandler\text\TextDebugtraceRenderer;

class FileExceptionHandlerTest extends TestCase
{
    /**
     * @throws FileInputException
     * @throws FileOutputException
     * @throws MakeDirectoryException
     */
    public function testHandleException()
    {
        $tmpdir = new File(__DIR__ . "/tmp");

        //======================================
        // exception_1.txt
        //======================================
        $file = new File("exception_1.txt", $tmpdir);

        $handler = new FileExceptionHandler($file, new TextDebugtraceRenderer);

        $e = new Exception("test");

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
        $file = new File("exception_2.txt", $tmpdir);

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
        $this->assertEquals('[1]knotlib\exceptionhandler\file\test\SampleException', $output[6] ?? null);
        $this->assertEquals('   sample exception', $output[8] ?? null);
        $this->assertEquals('[2]Exception', $output[10] ?? null);
        $this->assertEquals('   this is cause', $output[12] ?? null);
        $this->assertEquals('* Call Stack *', $output[14] ?? null);
        $this->assertEquals('-------------------------------------------------------------', $output[15] ?? null);
        $this->assertEquals('[0]knotlib\exception\KnotPhpException->__construct()', $output[16] ?? null);
        $this->assertEquals('[1]knotlib\exceptionhandler\file\test\SampleException->__construct()', $output[19] ?? null);
    }
}