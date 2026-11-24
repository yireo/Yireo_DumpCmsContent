<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Test\Integration;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\DumpCmsContent\Util\DumpCmsContent;
use Yireo\DumpCmsContent\Writer\DumpWriter;

/**
 * @magentoDbIsolation enabled
 */
class DumpCmsContentTest extends TestCase
{
    public function testDumpOnlyWritesInsideOutputFolder(): void
    {
        $block = $this->createBlock('x/../../../pub/yireo-dump-evil', '<p class="evil">evil</p>', [0]);

        $this->getDumpCmsContent()->execute();

        $file = $this->getFile('cms-block', (int)$block->getId(), 'x_pub_yireo-dump-evil');
        $this->assertSame('<p class="evil">evil</p>', $this->getVarDirectory()->readFile($file));

        $rootDirectory = $this->getFilesystem()->getDirectoryRead(DirectoryList::ROOT);
        $this->assertFalse($rootDirectory->isExist('pub/yireo-dump-evil.html'));

        foreach ($this->getVarDirectory()->read(DumpWriter::OUTPUT_FOLDER) as $path) {
            $this->assertMatchesRegularExpression('#^cms-output/cms-(block|page)-\d+-[a-zA-Z0-9_-]+\.html$#', $path);
        }
    }

    public function testInactiveContentIsOnlyDumpedWhenRequested(): void
    {
        $page = $this->createPage('yireo-dump-inactive', '<p>inactive</p>', false);
        $file = $this->getFile('cms-page', (int)$page->getId(), 'yireo-dump-inactive');

        $this->getDumpCmsContent()->execute();
        $this->assertFalse($this->getVarDirectory()->isExist($file));

        $this->getDumpCmsContent()->execute(true);
        $this->assertTrue($this->getVarDirectory()->isExist($file));
    }

    public function testSameIdentifierInDifferentStoresWritesSeparateFiles(): void
    {
        $block1 = $this->createBlock('yireo-dump-footer', 'footer all stores', [0]);
        $block2 = $this->createBlock('yireo-dump-footer', 'footer default store', [1]);

        $this->getDumpCmsContent()->execute();

        $varDirectory = $this->getVarDirectory();
        $this->assertSame(
            'footer all stores',
            $varDirectory->readFile($this->getFile('cms-block', (int)$block1->getId(), 'yireo-dump-footer'))
        );
        $this->assertSame(
            'footer default store',
            $varDirectory->readFile($this->getFile('cms-block', (int)$block2->getId(), 'yireo-dump-footer'))
        );
    }

    public function testStaleFilesAreRemoved(): void
    {
        $staleFile = $this->getFile('cms-page', 999999, 'stale');
        $this->getVarDirectory()->writeFile($staleFile, 'stale');

        $this->getDumpCmsContent()->execute();

        $this->assertFalse($this->getVarDirectory()->isExist($staleFile));
    }

    public function testEmptyContentIsSkipped(): void
    {
        $block = $this->createBlock('yireo-dump-empty', '', [0]);

        $this->getDumpCmsContent()->execute();

        $this->assertFalse(
            $this->getVarDirectory()->isExist($this->getFile('cms-block', (int)$block->getId(), 'yireo-dump-empty'))
        );
    }

    /**
     * @param int[] $storeIds
     */
    private function createBlock(string $identifier, string $content, array $storeIds): BlockInterface
    {
        $block = Bootstrap::getObjectManager()->get(BlockFactory::class)->create();
        $block->setIdentifier($identifier);
        $block->setTitle($identifier);
        $block->setContent($content);
        $block->setIsActive(true);
        $block->setData('store_id', $storeIds);

        return Bootstrap::getObjectManager()->get(BlockRepositoryInterface::class)->save($block);
    }

    private function createPage(string $identifier, string $content, bool $active): PageInterface
    {
        $page = Bootstrap::getObjectManager()->get(PageFactory::class)->create();
        $page->setIdentifier($identifier);
        $page->setTitle($identifier);
        $page->setContent($content);
        $page->setIsActive($active);
        $page->setData('store_id', [0]);

        return Bootstrap::getObjectManager()->get(PageRepositoryInterface::class)->save($page);
    }

    private function getFile(string $prefix, int $entityId, string $safeIdentifier): string
    {
        return DumpWriter::OUTPUT_FOLDER . '/' . $prefix . '-' . $entityId . '-' . $safeIdentifier . '.html';
    }

    private function getDumpCmsContent(): DumpCmsContent
    {
        return Bootstrap::getObjectManager()->get(DumpCmsContent::class);
    }

    private function getVarDirectory(): WriteInterface
    {
        return $this->getFilesystem()->getDirectoryWrite(DirectoryList::VAR_DIR);
    }

    private function getFilesystem(): Filesystem
    {
        return Bootstrap::getObjectManager()->get(Filesystem::class);
    }
}
