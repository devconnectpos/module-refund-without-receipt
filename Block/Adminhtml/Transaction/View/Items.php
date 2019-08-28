<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\RefundWithoutReceipt\Block\Adminhtml\Transaction\View;

use SM\RefundWithoutReceipt\Block\Adminhtml\Items\AbstractItems;

class Items extends AbstractItems
{
    /**
     * @return array
     */
    public function getColumns()
    {
        $columns = array_key_exists('columns', $this->_data) ? $this->_data['columns'] : [];
        return $columns;
    }

    /**
     * Retrieve required options from parent
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeToHtml()
    {
        if (!$this->getParentBlock()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid parent block for this block'));
        }
        $this->setTransaction($this->getParentBlock()->getTransaction());
        parent::_beforeToHtml();
    }

    /**
     * Retrieve refund without receipt items collection
     *
     * @return \SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getItemsCollection()
    {
        return $this->getTransaction()->getItemsCollection();
    }
}
