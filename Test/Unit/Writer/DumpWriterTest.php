<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Test\Unit\Writer;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use PHPUnit\Framework\TestCase;
use Yireo\DumpCmsContent\Writer\DumpWriter;

class DumpWriterTest extends TestCase
{
    public function testInvalidFileNamesAreRejected(): void
    {
        $filesystem = $this->createMock(Filesystem::class);
        $filesystem->expects($this->never())->method('getDirectoryWrite');
        $dumpWriter = new DumpWriter($filesystem);

        $invalidFileNames = [
            '../evil.html',
            'a/b.html',
            'a\\b.html',
            'evil.php',
            'evil.php.html',
            "evil.html\n",
            "evil\0.html",
            '',
        ];

        foreach ($invalidFileNames as $fileName) {
            try {
                $dumpWriter->writeContent($fileName, 'content');
                $this->fail('File name was accepted: ' . addcslashes($fileName, "\0..\37"));
            } catch (FileSystemException $exception) {
                $this->assertStringContainsString('Invalid CMS dump file name', $exception->getMessage());
            }
        }
    }
}
