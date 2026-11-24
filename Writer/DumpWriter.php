<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Writer;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;

class DumpWriter
{
    public const OUTPUT_FOLDER = 'cms-output';

    public function __construct(
        private readonly Filesystem $filesystem
    ) {
    }

    /**
     * @throws FileSystemException
     */
    public function clean(): void
    {
        $varDirectory = $this->getVarDirectory();
        $this->assertOutputFolderIsSafe($varDirectory);

        if ($varDirectory->isExist(self::OUTPUT_FOLDER)) {
            $varDirectory->delete(self::OUTPUT_FOLDER);
        }

        $varDirectory->create(self::OUTPUT_FOLDER);
    }

    /**
     * @throws FileSystemException
     */
    public function writeContent(string $fileName, string $content): void
    {
        if (!preg_match('/^[a-z0-9_-]+\.html\z/i', $fileName)) {
            throw new FileSystemException(__('Invalid CMS dump file name "%1"', $fileName));
        }

        $varDirectory = $this->getVarDirectory();
        $this->assertOutputFolderIsSafe($varDirectory);
        $varDirectory->writeFile(self::OUTPUT_FOLDER . '/' . $fileName, $content);
    }

    /**
     * @throws FileSystemException
     */
    private function assertOutputFolderIsSafe(WriteInterface $varDirectory): void
    {
        if (!$varDirectory->isExist(self::OUTPUT_FOLDER)) {
            return;
        }

        $driver = $varDirectory->getDriver();
        $expectedPath = rtrim((string)$driver->getRealPath($varDirectory->getAbsolutePath()), '/')
            . '/' . self::OUTPUT_FOLDER;
        $actualPath = (string)$driver->getRealPath($varDirectory->getAbsolutePath(self::OUTPUT_FOLDER));

        if ($actualPath !== $expectedPath) {
            throw new FileSystemException(
                __('CMS dump folder "%1" resolves to "%2"; refusing to use it', $expectedPath, $actualPath)
            );
        }
    }

    /**
     * @throws FileSystemException
     */
    private function getVarDirectory(): WriteInterface
    {
        return $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    }
}
