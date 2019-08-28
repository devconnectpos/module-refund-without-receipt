<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\RefundWithoutReceipt\Block\Adminhtml\Transaction\View\Items\Renderer;

/**
 * Adminhtml sales order item renderer
 *
 * @api
 * @since 100.0.2
 */
class DefaultRenderer extends \SM\RefundWithoutReceipt\Block\Adminhtml\Items\Renderer\DefaultRenderer
{

    /**
     * Message helper
     *
     * @var \Magento\GiftMessage\Helper\Message
     */
    protected $_messageHelper;

    /**
     * Checkout helper
     *
     * @var \Magento\Checkout\Helper\Data
     */
    protected $_checkoutHelper;

    /**
     * Giftmessage object
     *
     * @var \Magento\GiftMessage\Model\Message
     */
    protected $_giftMessage = [];

    /**
     * @param \Magento\Backend\Block\Template\Context                   $context
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface      $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Framework\Registry                               $registry
     * @param \Magento\GiftMessage\Helper\Message                       $messageHelper
     * @param \Magento\Checkout\Helper\Data                             $checkoutHelper
     * @param array                                                     $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\GiftMessage\Helper\Message $messageHelper,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        array $data = []
    ) {
        $this->_checkoutHelper = $checkoutHelper;
        $this->_messageHelper  = $messageHelper;
        parent::__construct($context, $stockRegistry, $stockConfiguration, $registry, $data);
    }

    /**
     * Get Refund item
     *
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItem
     */
    public function getItem()
    {
        return $this->_getData('item');
    }

    /**
     * @param \Magento\Framework\DataObject|\SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItem $item
     * @param string                                                                                $column
     * @param null                                                                                  $field
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getColumnHtml(\Magento\Framework\DataObject $item, $column, $field = null)
    {
        $html = '';
        switch ($column) {
            case 'product':
                $html .= $this->getColumnHtml($item, 'name');
                break;

            case 'product-price':
                $html = $this->displayPriceAttribute('product_price');
                break;

            case 'subtotal':
                $html = $this->displayPriceAttribute('sub_total');
                break;
            case 'total':
                $html = $this->displayPriceAttribute('row_total');
                break;

            default:
                $html = parent::getColumnHtml($item, $column, $field);
        }

        return $html;
    }

    /**
     * @return array
     * @since 100.1.0
     */
    public function getColumns()
    {
        $columns = array_key_exists('columns', $this->_data) ? $this->_data['columns'] : [];

        return $columns;
    }
}
