<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use PHPUnit\Framework\TestCase;
use stdClass;
use Iterator;
use IteratorAggregate;
use Byte\Caller\CallerInterface;

class PipeTest extends TestCase
{
    use IteratorMockTrait;

    public function testInstance()
    {
        $iterator = $this->getMockForAbstractClass(Iterator::class);
        $invoker  = $this->getMockForAbstractClass(CallerInterface::class);
        $pipe     = new Pipe($iterator, $invoker);

        $this->assertInstanceOf(PipeInterface::class, $pipe);
        $this->assertInstanceOf(IteratorAggregate::class, $pipe);
    }

    public function testPopulation()
    {
        $iterator1 = $this->getMockForAbstractClass(Iterator::class);
        $iterator2 = $this->getMockForAbstractClass(Iterator::class);
        $invoker   = $this->getMockForAbstractClass(CallerInterface::class);
        $pipe      = new Pipe($iterator1, $invoker);

        $pipe = $pipe->populate($iterator2);

        $this->assertSame($iterator2, $pipe->getIterator());
    }

    public function testPass()
    {
        $elements = [1, 2, 3];
        $iterator = $this->mockIterator($elements);
        $args     = ['a', 'b'];
        $invoker  = $this->getMockForAbstractClass(CallerInterface::class);
        $callback = function () {
        };

        foreach ($elements as $sequence => $element) {
            $invoker->expects($this->at($sequence))
                ->method('call')
                ->with(
                    $this->equalTo($callback),
                    $this->callback(function ($subject) use ($element, $args) {
                        return $subject[0] == $element && array_slice($subject, 1) == $args;
                    })
                )
                ->will($this->returnValue($element + 1));
        }

        $pipe = new Pipe($iterator, $invoker);
        $pipe->pass($callback, $args);
    }

    public function testWrap()
    {
        $iterator1 = $this->getMockForAbstractClass(Iterator::class);
        $iterator2 = $this->getMockForAbstractClass(Iterator::class);
        $args     = ['a', 'b'];
        $callback = function () {
        };
        $invoker  = $this->getMockForAbstractClass(CallerInterface::class);
        $invoker->expects($this->once())
            ->method('call')
            ->with(
                $this->equalTo($callback),
                $this->callback(
                    function ($subject) use ($iterator1, $args) {
                        return $subject[0] == $iterator1 && array_slice($subject, 1) == $args;
                    }
                )
            )
            ->will($this->returnValue($iterator2));

        $pipe = new Pipe($iterator1, $invoker);
        $pipe->wrap($callback, $args);
    }
}
