<?php
declare(strict_types=1);

namespace knotlib\exceptionhandler\file;

use Throwable;

use Stk2k\File\Exception\FileOutputException;
use Stk2k\File\Exception\MakeDirectoryException;
use Stk2k\File\File;

use knotlib\exceptionhandler\DebugtraceRendererInterface;
use knotlib\exceptionhandler\ExceptionHandlerInterface;

class FileExceptionHandler implements ExceptionHandlerInterface
{
    /** @var File */
    private $renderer;

    /** @var DebugtraceRendererInterface */
    private $file;

    /**
     * Charcoal_ConsoleExceptionHandler constructor.
     *
     * @param File $file
     * @param DebugtraceRendererInterface $renderer
     */
    public function __construct(File $file, DebugtraceRendererInterface $renderer)
    {
        $this->file = $file;
        $this->renderer = $renderer;
    }

    /**
     * @param Throwable $e
     * @throws MakeDirectoryException
     * @throws FileOutputException
     */
    public function handleException(Throwable $e) : void
    {
        // Render exception
        $output = $this->renderer->output($e);

        // make directory
        $dir = $this->file->getParent();

        if (!$dir->exists()){
            $dir->makeDirectory();
        }

        // output
        $this->file->putContents($output);
    }
}