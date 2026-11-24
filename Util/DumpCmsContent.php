<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Util;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Yireo\DumpCmsContent\DataProvider\CmsBlockProvider;
use Yireo\DumpCmsContent\DataProvider\CmsPageProvider;
use Yireo\DumpCmsContent\Writer\DumpWriter;

class DumpCmsContent
{
    public function __construct(
        private readonly CmsBlockProvider $cmsBlockProvider,
        private readonly CmsPageProvider $cmsPageProvider,
        private readonly DumpWriter $dumpWriter,
        private readonly FileNameGenerator $fileNameGenerator
    ) {
    }

    /**
     * @return int Number of files written
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function execute(bool $includeInactive = false): int
    {
        $this->dumpWriter->clean();
        $count = 0;

        foreach ($this->cmsBlockProvider->getCmsBlocks($includeInactive) as $cmsBlock) {
            $count += $this->write(
                'cms-block',
                (int)$cmsBlock->getId(),
                (string)$cmsBlock->getIdentifier(),
                (string)$cmsBlock->getContent()
            );
        }

        foreach ($this->cmsPageProvider->getCmsPages($includeInactive) as $cmsPage) {
            $count += $this->write(
                'cms-page',
                (int)$cmsPage->getId(),
                (string)$cmsPage->getIdentifier(),
                (string)$cmsPage->getContent()
            );
        }

        return $count;
    }

    /**
     * @throws FileSystemException
     */
    private function write(string $prefix, int $entityId, string $identifier, string $content): int
    {
        if ($content === '') {
            return 0;
        }

        $fileName = $this->fileNameGenerator->generate($prefix, $entityId, $identifier);
        $this->dumpWriter->writeContent($fileName, $content);

        return 1;
    }
}
