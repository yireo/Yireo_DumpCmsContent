<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\DataProvider;

use Magento\Cms\Model\Page;
use Magento\Cms\Model\PageRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CmsPageProvider
{
    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * CmsPageProvider constructor.
     * @param PageRepository $pageRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        PageRepository $pageRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->pageRepository = $pageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return Page[]
     */
    public function getCmsPages(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->pageRepository->getList($searchCriteria)->getItems();
    }
}
