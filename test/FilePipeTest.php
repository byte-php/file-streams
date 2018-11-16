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
use Byte\Caller\CallerInterface;
use org\bovigo\vfs\vfsStream as vfs;

class FilePipeTest extends TestCase
{
    use IteratorMockTrait;

    public function testInstance()
    {
        $pipe = $this->getMockForAbstractClass(PipeInterface::class);
        $pipe = new FilePipe($pipe);

        $this->assertInstanceOf(FilePipeInterface::class, $pipe);
    }

    public function testIteratorGetting()
    {
        $pipe = $this->getMockForAbstractClass(PipeInterface::class);
        $pipe->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue($this->getMockForAbstractClass(Iterator::class)));

        $pipe = new FilePipe($pipe);
        $this->assertInstanceOf(Iterator::class, $pipe->getIterator());
    }

    public function testSourceLoading()
    {
        $pipe = $this->getMockForAbstractClass(PipeInterface::class);
        $pipe->expects($this->once())
            ->method('populate')
            ->with($this->isInstanceOf(Iterator::class));

        $structure = [
            'dir1' => [
                'file1' => 'contents 1',
                'file2' => 'contents 2',
            ]
        ];
        vfs::setup('/', null, $structure);

        $pipe = new FilePipe($pipe);
        $pipe->src(vfs::url('/dir1/*'));
    }

    public function testPiping()
    {
        $structure = [
            'dir1' => [
                'file1' => 'contents 1',
                'file2' => 'contents 2',
            ]
        ];
        vfs::setup('/', null, $structure);

        $pipe = $this->getMockForAbstractClass(PipeInterface::class);
        $pipe->expects($this->once())
            ->method('populate')
            ->with($this->isInstanceOf(Iterator::class));

        $pipe = new FilePipe($pipe);
        $pipe->src(vfs::url('/dir1/*'));

        $result = $pipe->pipe(function ($file) {
        });

        $this->assertNotSame($pipe, $result);
    }

    public function testSourceWriting()
    {
        $structure = [
            'dir1' => [
                'file1' => 'contents 1',
            ],
            'dir2' => []
        ];
        vfs::setup('/', null, $structure);

        $stream = fopen(vfs::url('/dir1/file1'), 'rb');

        $pipe = $this->getMockForAbstractClass(PipeInterface::class);
        $pipe = new FilePipe($pipe);
        $file = $this->getMockForAbstractClass(FileInterface::class);
        $file->expects($this->once())
            ->method('getFilename')
            ->will($this->returnValue('file1'));
        $file->expects($this->once())
            ->method('getStream')
            ->wilL($this->returnValue($stream));

        $callback = $pipe->dest(vfs::url('/dir2'));
        $callback($file);

        fseek($stream, 0, SEEK_SET);
        $this->assertSame(
            stream_get_contents($stream),
            file_get_contents(vfs::url('/dir2/file1'))
        );
    }
}
