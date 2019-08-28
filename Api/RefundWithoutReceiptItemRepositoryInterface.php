<?php
/**
 * Created by Nomad
 * Date: 7/10/19
 * Time: 4:22 PM
 */

namespace SM\RefundWithoutReceipt\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface;

interface RefundWithoutReceiptItemRepositoryInterface
{

    /**
     * @param \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface $refundWithoutReceiptItem
     *
     * @return \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface
     */
    public function save(RefundWithoutReceiptItemInterface $refundWithoutReceiptItem);

    /**
     * @param int $itemId
     *
     * @return \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface
     */
    public function get($itemId);

    /**
     * Get product list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
