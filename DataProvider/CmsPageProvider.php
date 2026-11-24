<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\DataProvider;

use Generator;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;

class CmsPageProvider
{
    private const PAGE_SIZE = 100;

    public function __construct(
        private readonly PageRepositoryInterface $pageRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * @return Generator<PageInterface>
     * @throws LocalizedException
     */
    public function getCmsPages(bool $includeInactive = false): Generator
    {
        $currentPage = 1;
        do {
            if (!$includeInactive) {
                $this->searchCriteriaBuilder->addFilter(PageInterface::IS_ACTIVE, 1);
            }

            $this->searchCriteriaBuilder->setPageSize(self::PAGE_SIZE);
            $this->searchCriteriaBuilder->setCurrentPage($currentPage);
            $searchResults = $this->pageRepository->getList($this->searchCriteriaBuilder->create());

            foreach ($searchResults->getItems() as $cmsPage) {
                yield $cmsPage;
            }

            $currentPage++;
        } while (($currentPage - 1) * self::PAGE_SIZE < $searchResults->getTotalCount());
    }
}
