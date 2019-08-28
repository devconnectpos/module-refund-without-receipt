<?php
/**
 * Created by Nomad
 * Date: 6/21/19
 * Time: 5:40 PM
 */

namespace SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptTransaction;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction as RefundWithoutReceiptTransactionModel;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptTransaction as RefundWithoutReceiptTransactionResourceModel;

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
            RefundWithoutReceiptTransactionModel::class,
            RefundWithoutReceiptTransactionResourceModel::class
        );
    }
}
