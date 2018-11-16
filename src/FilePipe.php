<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use Iterator;
use GlobIterator;
use AppendIterator;
use IteratorIterator;
use IteratorAggregate;
use InvalidArgumentException;

/**
 * Gulp-like file pipe
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
final class FilePipe implements FilePipeInterface
{
    /**
     * Internal pipe
     *
     * @var PipeInterface
     */
    protected $pipe;

    public function __construct(PipeInterface $pipe)
    {
        $this->pipe = $pipe;
    }

    /** @inheritdoc */
    public function getIterator(): Iterator
    {
        return $this->pipe->getIterator();
    }

    /** @inheritdoc */
    public function src(string ...$sources): FilePipeInterface
    {
        $iterator = new AppendIterator();
        $flags    = GlobIterator::CURRENT_AS_PATHNAME | GlobIterator::SKIP_DOTS;
        foreach ($sources as $source) {
            $source = new GlobIterator($source, $flags);
            $source = new FileIterator($source);
            $iterator->append($source);
        }

        $pipe = $this->pipe->populate($iterator);

        return new static($pipe);
    }

    /** @inheritdoc */
    public function pipe($callback): FilePipeInterface
    {
        $pipe = $this->pipe->pass($callback);

        return new static($pipe);
    }

    /** @inheritdoc */
    public function dest(string $directory): callable
    {
        return function (FileInterface $file) use ($directory) {
            $path   = $directory . DIRECTORY_SEPARATOR . $file->getFilename();
            $dest   = fopen($path, 'wb+');
            $source = $file->getStream();

            stream_copy_to_stream($source, $dest);
            fseek($dest, 0, SEEK_SET);

            return new File($path, $dest);
        };
    }
}
