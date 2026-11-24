<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\DataProvider;

use Generator;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;

class CmsBlockProvider
{
    private const PAGE_SIZE = 100;

    public function __construct(
        private readonly BlockRepositoryInterface $blockRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * @return Generator<BlockInterface>
     * @throws LocalizedException
     */
    public function getCmsBlocks(bool $includeInactive = false): Generator
    {
        $currentPage = 1;
        do {
            if (!$includeInactive) {
                $this->searchCriteriaBuilder->addFilter(BlockInterface::IS_ACTIVE, 1);
            }

            $this->searchCriteriaBuilder->setPageSize(self::PAGE_SIZE);
            $this->searchCriteriaBuilder->setCurrentPage($currentPage);
            $searchResults = $this->blockRepository->getList($this->searchCriteriaBuilder->create());

            foreach ($searchResults->getItems() as $cmsBlock) {
                yield $cmsBlock;
            }

            $currentPage++;
        } while (($currentPage - 1) * self::PAGE_SIZE < $searchResults->getTotalCount());
    }
}
