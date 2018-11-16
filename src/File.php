<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use InvalidArgumentException;

/**
 * An object that represents a file with its contents
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
final class File implements FileInterface
{
    /**
     * Name of the file
     *
     * @var string
     */
    protected $filename;

    /**
     * Directory of the file
     *
     * @var string
     */
    protected $path;

    /**
     * Stream with contents of the file.
     * Typically fopen()'ed resource.
     *
     * @var resource
     */
    protected $stream;

    public function __construct(string $pathname, $stream = null)
    {
        $this->filename = basename($pathname);
        $this->path     = dirname($pathname);

        if ($stream == null) {
            $stream = fopen($pathname, 'rb');
        }

        $this->setStream($stream);
    }

    public function __destruct()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }

    /** @inheritdoc */
    public function __clone()
    {
        $stream = fopen('php://temp', 'wb+');
        stream_copy_to_stream($this->stream, $stream);
        fseek($stream, 0, SEEK_SET);
        $this->stream = $stream;
    }

    /** @inheritdoc */
    public function getStream()
    {
        return $this->stream;
    }

    /** @inheritdoc */
    public function setStream($stream): FileInterface
    {
        if (! is_resource($stream)) {
            throw new InvalidArgumentException("Stream must be a valid file resource");
        }

        // close previous stream
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }

        $this->stream = $stream;

        return $this;
    }

    /** @inheritdoc */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /** @inheritdoc */
    public function setFilename(string $filename): FileInterface
    {
        $this->filename = $filename;

        return $this;
    }

    /** @inheritdoc */
    public function getPath(): string
    {
        return $this->path;
    }

    /** @inheritdoc */
    public function setPath(string $path): FileInterface
    {
        $this->path = $path;

        return $this;
    }

    /** @inheritdoc */
    public function getPathname(): string
    {
        return $this->getPath() . DIRECTORY_SEPARATOR . $this->getFilename();
    }
}
