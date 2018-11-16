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

class FileTest extends TestCase
{
    public function testInstance()
    {
        vfs::setup('/', null, ['dir'=> ['file' => 'contents']]);
        $file = new File(vfs::url('/dir/file'));

        $this->assertInstanceOf(FileInterface::class, $file);
    }

    public function testCloning()
    {
        vfs::setup('/', null, ['dir'=> ['file' => 'contents']]);
        $file1 = new File(vfs::url('/dir/file'));
        $file2 = clone $file1;

        $this->assertNotSame($file1->getStream(), $file2->getStream());
    }

    /** @expectedException InvalidArgumentException */
    public function testSettingStreamOnInvalidStreamByConstruct()
    {
        new File('path', 'not-a-stream');
    }

    /** @expectedException InvalidArgumentException */
    public function testSettingStreamOnInvalidStreamByMethod()
    {
        vfs::setup('/', null, ['file' => 'contents']);
        $file1 = new File(vfs::url('/file'));
        $file1->setStream('not-a-stream');
    }

    public function testSettingStream()
    {
        vfs::setup('/', null, ['dir' => ['file1' => 'contents 1', 'file2' => 'contents 2']]);
        $file = new File(vfs::url('/dir/file1'));
        $stream = fopen(vfs::url('/dir/file2'), 'rb');
        $file->setStream($stream);
        $this->assertSame($stream, $file->getStream());
    }

    public function testFilenameGettingAndSetting()
    {
        vfs::setup('/', null, ['dir' => ['file1' => 'contents 1', 'file2' => 'contents 2']]);
        $file = new File(vfs::url('/dir/file1'));
        $this->assertEquals('file1', $file->getFilename());
        $file->setFilename('file2');
        $this->assertEquals('file2', $file->getFilename());

        $this->assertNotEquals(
            file_get_contents($file->getPathname()),
            stream_get_contents($file->getStream())
        );
    }

    public function testFileGettingAndSetting()
    {
        vfs::setup('/', null, ['dir1' => ['file1' => 'contents 1'], 'dir2' => ['file1' => 'contents 2']]);
        $file = new File(vfs::url('/dir1/file1'));
        $this->assertEquals('vfs:///dir1', $file->getPath());
        $file->setPath('vfs:///dir2');
        $this->assertEquals('vfs:///dir2', $file->getPath());

        $this->assertNotEquals(
            file_get_contents($file->getPathname()),
            stream_get_contents($file->getStream())
        );
    }
}
