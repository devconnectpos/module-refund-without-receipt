<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\RefundWithoutReceipt\Block\Adminhtml\Items\Renderer;

use Magento\Sales\Model\Order\Item;

/**
 * Adminhtml sales order item renderer
 *
 * @api
 * @since 100.0.2
 */
class DefaultRenderer extends \SM\RefundWithoutReceipt\Block\Adminhtml\Items\AbstractItems
{
    /**
     * Get order item
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->_getData('item');//->getOrderItem();
    }
}
