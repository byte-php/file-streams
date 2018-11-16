<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

/**
 * An interface for an object that represents a file with its contents
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
interface FileInterface
{
    /**
     * Clone a file and its contents
     */
    public function __clone();

    /**
     * Get a stream resource of the contents of the file
     *
     * @return resource
     */
    public function getStream();

    /**
     * Set a new stream for the file
     *
     * @param  resource      $stream Stream resource
     * @return FileInterface
     */
    public function setStream($stream): FileInterface;

    /**
     * Fetch a filename of the file
     *
     * @return string Filename
     */
    public function getFilename(): string;

    /**
     * Overwrite the filename of the file
     *
     * @param  string        $filename New filename
     * @return FileInterface
     */
    public function setFilename(string $filename): FileInterface;

    /**
     * Get path to the file (without the filename)
     *
     * @return string Path to the file
     */
    public function getPath(): string;

    /**
     * Overwrite the path to the file
     *
     * @param  string        $path New path
     * @return FileInterface
     */
    public function setPath(string $path): FileInterface;

    /**
     * Get the concatenated path and filename (full path to the file).
     *
     * @return string Pathname
     */
    public function getPathname(): string;
}
