<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use Iterator;
use ArrayIterator;
use Byte\Caller\CallerInterface;

/**
 * Pipe object for wrapping over iterators and iterators' elements.
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
final class Pipe implements PipeInterface
{
    /**
     * Iterator that stores elements to pass to callback
     *
     * @var Iterator
     */
    private $iterator;

    /**
     * Invoker object that calls callbacks passed to the pass method
     *
     * @var CallerInterface
     */
    private $caller;

    /**
     * @param Iterator         $iterator Iterator that stores elements to pass to callback
     * @param CallerInterface  $caller  Invoker object that calls callbacks
     */
    public function __construct(Iterator $iterator, CallerInterface $caller)
    {
        $this->iterator = $iterator;
        $this->caller   = $caller;
    }

    /** @inheritdoc */
    public function getIterator(): Iterator
    {
        return $this->iterator;
    }

    /** @inheritdoc */
    public function populate(Iterator $iterator): PipeInterface
    {
        return new self($iterator, $this->caller);
    }

    /** @inheritdoc */
    public function pass($callback, array $args = []): PipeInterface
    {
        $iterator = new ArrayIterator();

        foreach ($this->iterator as $element) {
            $arguments = array_merge([$element], $args);
            $element   = $this->caller->call($callback, $arguments);
            $iterator->append($element);
        }

        return $this->populate($iterator);
    }

    /** @inheritdoc */
    public function wrap($callback, array $args = []): PipeInterface
    {
        $arguments = array_merge([$this->iterator], $args);
        $iterator  = $this->caller->call($callback, $arguments);

        return $this->populate($iterator);
    }
}
