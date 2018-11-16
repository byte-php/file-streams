<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use Iterator;
use IteratorAggregate;

/**
 * Interface for pipe object for wrapping over iterators and iterators' elements.
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
interface PipeInterface extends IteratorAggregate
{
    /**
     * Populate pipe with new iterator.
     *
     * @param  Iterator      $iterator Iterator with data to populate with
     * @return PipeInterface           Pipe
     */
    public function populate(Iterator $iterator): PipeInterface;

    /**
     * Pass each element of the iterator as a parameter to the callback.
     *
     * @param  mixed         $callback Callback that handles elements of the iterator.
     *                                 It must return an element to feed back the iterator.
     * @param  array         $args     Additional arguments to pass to the callback
     * @return PipeInterface           Pipe
     */
    public function pass($callback, array $args = []): PipeInterface;

    /**
     * Pass a whole iterator as a parameter to the callback (wrap iterator with callback).
     *
     * @param  mixed         $callback Callback that handles the iterator.
     *                                 It must return an iterator.
     * @param  array         $args     Additional arguments to pass to the callback
     * @return PipeInterface           Pipe
     */
    public function wrap($callback, array $args = []): PipeInterface;
}
