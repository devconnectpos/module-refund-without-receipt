<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\RefundWithoutReceipt\Block\Adminhtml\Items\Column;

use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Model\Product\OptionFactory;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Registry;
use SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItem;

class DefaultColumn extends \SM\RefundWithoutReceipt\Block\Adminhtml\Items\AbstractItems
{
    /**
     * Option factory
     *
     * @var \Magento\Catalog\Model\Product\OptionFactory
     */
    protected $_optionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\Product\OptionFactory $optionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        StockRegistryInterface $stockRegistry,
        StockConfigurationInterface $stockConfiguration,
        Registry $registry,
        OptionFactory $optionFactory,
        array $data = []
    ) {
        $this->_optionFactory = $optionFactory;
        parent::__construct($context, $stockRegistry, $stockConfiguration, $registry, $data);
    }

    /**
     * @return RefundWithoutReceiptItem
     */
    public function getItem()
    {
        $item = $this->_getData('item');
        if ($item instanceof RefundWithoutReceiptItem) {
            return $item;
        } else {
            return $item->getTransactionItem();
        }
    }

    /**
     * Get order options
     *
     * @return array|bool
     */
    public function getOrderOptions()
    {
        if ($options = $this->getItem()->getProductOptions()) {
            $options = json_decode($options, true);
            if (isset($options['options'])) {
                return $options['options'];
            } else if (isset($options['attributes_info'])) {
                return $options['attributes_info'];
            }
            return $options;
        }
        return false;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->getItem()->getProductSku();
    }

    /**
     * Calculate total amount for the item
     *
     * @param RefundWithoutReceiptItem $item
     * @return mixed
     */
    public function getTotalAmount($item)
    {
        $totalAmount = $item->getProductPrice();

        return $totalAmount;
    }

    /**
     * Calculate base total amount for the item
     *
     * @param RefundWithoutReceiptItem $item
     * @return mixed
     */
    public function getBaseTotalAmount($item)
    {
        $baseTotalAmount =  $item->getBaseProductPrice();

        return $baseTotalAmount;
    }
}
