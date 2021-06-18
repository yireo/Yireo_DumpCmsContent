<?php declare(strict_types=1);

namespace Yireo\DumpCmsContent\DataProvider;

use Magento\Cms\Model\Block;
use Magento\Cms\Model\BlockRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CmsBlockProvider
{
    /**
     * @var BlockRepository
     */
    private $blockRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * CmsBlockProvider constructor.
     * @param BlockRepository $blockRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        BlockRepository $blockRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->blockRepository = $blockRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return Block[]
     */
    public function getCmsBlocks(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->blockRepository->getList($searchCriteria)->getItems();
    }
}
