<?php
/**
 * Created by Nomad
 * Date: 6/24/19
 * Time: 10:58 AM
 */

namespace SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItem as RefundWithoutReceiptItemModel;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem as RefundWithoutReceiptItemResourceModel;

class Collection extends AbstractCollection
{

    /**
     * Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            RefundWithoutReceiptItemModel::class,
            RefundWithoutReceiptItemResourceModel::class
        );
    }
}
