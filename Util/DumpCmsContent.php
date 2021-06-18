<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\Util;

use Magento\Framework\Exception\FileSystemException;
use Yireo\DumpCmsContent\DataProvider\CmsBlockProvider;
use Yireo\DumpCmsContent\DataProvider\CmsPageProvider;
use Yireo\DumpCmsContent\Writer\DumpWriter;

class DumpCmsContent
{
    /**
     * @var CmsBlockProvider
     */
    private $cmsBlockProvider;

    /**
     * @var CmsPageProvider
     */
    private $cmsPageProvider;

    /**
     * @var DumpWriter
     */
    private $dumpWriter;

    /**
     * DumpCmsContent constructor.
     * @param CmsBlockProvider $cmsBlockProvider
     * @param CmsPageProvider $cmsPageProvider
     * @param DumpWriter $dumpWriter
     */
    public function __construct(
        CmsBlockProvider $cmsBlockProvider,
        CmsPageProvider $cmsPageProvider,
        DumpWriter $dumpWriter
    ) {
        $this->cmsBlockProvider = $cmsBlockProvider;
        $this->cmsPageProvider = $cmsPageProvider;
        $this->dumpWriter = $dumpWriter;
    }

    /**
     * @throws FileSystemException
     */
    public function execute()
    {
        foreach ($this->cmsBlockProvider->getCmsBlocks() as $cmsBlock) {
            $cmsBlockId = 'cms-block-' . $cmsBlock->getIdentifier();
            $this->dumpWriter->writeContent($cmsBlockId, $cmsBlock->getContent());
        }

        foreach ($this->cmsPageProvider->getCmsPages() as $cmsPage) {
            $cmsPageId = 'cms-page-' . $cmsPage->getIdentifier();
            $this->dumpWriter->writeContent($cmsPageId, $cmsPage->getContent());
        }
    }
}
