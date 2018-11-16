<?php declare(strict_types=1);
/**
 * File streams
 *
 * @author  Alwynn <alwynn.github@gmail.com>
 * @package byte/file-streams
 */
namespace Byte\Streams;

use PHPUnit\Framework\TestCase;
use Iterator;
use ArrayIterator;
use org\bovigo\vfs\vfsStream as vfs;

class FileIteratorTest extends TestCase
{
    public function testInstance()
    {
        $iterator = $this->getMockForAbstractClass(Iterator::class);
        $iterator = new FileIterator($iterator);

        $this->assertInstanceOf(Iterator::class, $iterator);
    }

    public function testIteration()
    {
        $structure = [
            'dir1' => [
                'file1' => 'contents 1',
                'file2' => 'contents 2',
            ],
            'dir2' => [
                'file3' => 'contents 3'
            ]
        ];
        vfs::setup('/', null, $structure);

        $iterator = [vfs::url('/dir1/file1'), vfs::url('/dir1/file2'), vfs::url('/dir2/file3')];
        $iterator = new ArrayIterator($iterator);
        $iterator = new FileIterator($iterator);

        foreach ($iterator as $sequence => $file) {
            $this->assertInstanceOf(FileInterface::class, $file);
            $this->assertSame(
                file_get_contents($file->getPathname()),
                stream_get_contents($file->getStream())
            );
        }
    }
}
