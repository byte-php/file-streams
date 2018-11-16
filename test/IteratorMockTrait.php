<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use Iterator;

trait IteratorMockTrait
{
    /** @see https://gist.github.com/h4cc/6607543 */
    protected function mockIterator(array $items): Iterator
    {
        $iterator = $this->getMockBuilder(Iterator::class)
            ->setMethods(['rewind', 'valid', 'current', 'key', 'next'])
            ->getMockForAbstractClass();

        $counter = 0;
        $iterator->expects($this->at($counter++))->method('rewind');
        foreach ($items as $k => $v) {
            $iterator->expects($this->at($counter++))->method('valid')->will($this->returnValue(true));
            $iterator->expects($this->at($counter++))->method('current')->will($this->returnValue($v));
            $iterator->expects($this->at($counter++))->method('next');
        }
        $iterator->expects($this->at($counter))->method('valid')->will($this->returnValue(false));

        return $iterator;
    }
}
