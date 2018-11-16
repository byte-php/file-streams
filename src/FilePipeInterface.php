<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use IteratorAggregate;
use Invoker\InvokerInterface;

/**
 * Interface for gulp-like file pipe
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
interface FilePipeInterface extends IteratorAggregate
{
    /**
     * Read source directory for files. Source parameter should be a
     * glob() function compatible pattern.
     *
     * @param  string[] $source Glob patterns
     * @return
     */
    public function src(string ...$source): FilePipeInterface;

    /**
     * Pipe a callback
     *
     * @param  callback          $callback A callback to be passed to the pipe
     * @return FilePipeInterface
     */
    public function pipe($callback): FilePipeInterface;

    /**
     * Wrapper for a callback that will write all files on pipe to the directory
     *
     * @param  string   $directory Directory to write files to
     * @return callable
     */
    public function dest(string $directory): callable;
}
