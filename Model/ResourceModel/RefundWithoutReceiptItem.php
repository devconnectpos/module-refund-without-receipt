<?php
/**
 * Created by Nomad
 * Date: 6/21/19
 * Time: 5:39 PM
 */

namespace SM\RefundWithoutReceipt\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class RefundWithoutReceiptItem extends AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sm_refund_without_receipt_item', 'item_id');
    }
}
