<?php
/**
 * Created by Nomad
 * Date: 7/10/19
 * Time: 4:28 PM
 */

namespace SM\RefundWithoutReceipt\Model;

use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Model\ResourceModel\Metadata;
use SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface;
use SM\RefundWithoutReceipt\Api\RefundWithoutReceiptItemRepositoryInterface;
use SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItemFactory as RefundWithoutReceiptItemFactory;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem as RefundWithoutReceiptItemResourceModel;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem\Collection;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem\CollectionFactory as RefundWithoutReceiptItemCollectionFactory;

class RefundWithoutReceiptItemRepository implements RefundWithoutReceiptItemRepositoryInterface
{

    /**
     * @var \Magento\Sales\Model\ResourceModel\Metadata
     */
    protected $metadata;
    /**
     * @var \Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory
     */
    protected $searchResultFactory;
    /**
     * @var \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItemFactory
     */
    protected $refundWithoutReceiptItemFactory;
    /**
     * @var RefundWithoutReceiptItemResourceModel
     */
    protected $resourceModel;
    /**
     * @var RefundWithoutReceiptItemCollectionFactory
     */
    protected $collectionFactory;

    /**
     * RefundWithoutReceiptItemRepository constructor.
     *
     * @param \Magento\Sales\Model\ResourceModel\Metadata                             $metadata
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory               $searchResultFactory
     * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItemFactory          $refundWithoutReceiptItemFactory
     * @param RefundWithoutReceiptItemResourceModel                                   $resourceModel
     * @param RefundWithoutReceiptItemCollectionFactory                               $collectionFactory
     */
    public function __construct(
        Metadata $metadata,
        SearchResultFactory $searchResultFactory,
        RefundWithoutReceiptItemFactory $refundWithoutReceiptItemFactory,
        RefundWithoutReceiptItemResourceModel $resourceModel,
        RefundWithoutReceiptItemCollectionFactory $collectionFactory
    ) {
        $this->metadata                        = $metadata;
        $this->searchResultFactory             = $searchResultFactory;
        $this->refundWithoutReceiptItemFactory = $refundWithoutReceiptItemFactory;
        $this->resourceModel                   = $resourceModel;
        $this->collectionFactory               = $collectionFactory;
    }

    /**
     * @param \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface $refundWithoutReceiptItem
     *
     * @return \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Exception
     */
    public function save(RefundWithoutReceiptItemInterface $refundWithoutReceiptItem)
    {
        try {
            $this->resourceModel->save($refundWithoutReceiptItem);
        } catch (AlreadyExistsException $e) {
            throw new AlreadyExistsException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new Exception(__($e->getMessage()));
        }

        return $refundWithoutReceiptItem;
    }

    /**
     * @param int $itemId
     *
     * @return \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($itemId)
    {
        $item = $this->refundWithoutReceiptItemFactory->create();
        $this->resourceModel->load($item, $itemId);
        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Unable to find refund item with ID "%1"', $itemId));
        }

        return $item;
    }

    /**
     * Get product list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[]     = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
