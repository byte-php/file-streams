<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use IteratorIterator;

/**
 * Iterator that creates a new File object for each of element it contains.
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
class FileIterator extends IteratorIterator
{
    /**
     * @inheritdoc
     *
     * Creates a file object with stream copied from original file resource.
     *
     * @return File
     */
    public function current()
    {
        $path   = parent::current();
        $source = fopen($path, 'rb');
        $stream = fopen('php://temp', 'wb+');

        stream_copy_to_stream($source, $stream);
        fseek($stream, 0, SEEK_SET);
        fclose($source);

        return new File($path, $stream);
    }
}
