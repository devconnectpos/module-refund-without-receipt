<?php
/**
 * Created by Nomad
 * Date: 7/12/19
 * Time: 4:51 PM
 */

namespace SM\RefundWithoutReceipt\Block\Adminhtml\Items;

use Magento\Backend\Block\Template\Context;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\BlockInterface;
use RuntimeException;

class AbstractItems extends \Magento\Backend\Block\Template
{
    /**
     * Block alias fallback
     */
    const DEFAULT_TYPE = 'default';

    /**
     * Renderers for other column with column name key
     * block    => the block name
     * template => the template file
     * renderer => the block object
     *
     * @var array
     */
    protected $_columnRenders = [];

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $stockRegistry;

    /**
     * @var \Magento\CatalogInventory\Api\StockConfigurationInterface
     */
    protected $stockConfiguration;

    /**
     * @param \Magento\Backend\Block\Template\Context                   $context
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface      $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Framework\Registry                               $registry
     * @param array                                                     $data
     */
    public function __construct(
        Context $context,
        StockRegistryInterface $stockRegistry,
        StockConfigurationInterface $stockConfiguration,
        Registry $registry,
        array $data = []
    ) {
        $this->stockRegistry      = $stockRegistry;
        $this->stockConfiguration = $stockConfiguration;
        $this->coreRegistry      = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Add column renderers
     *
     * @param array $blocks
     *
     * @return \SM\RefundWithoutReceipt\Block\Adminhtml\Items\AbstractItems
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setColumnRenders(array $blocks)
    {
        foreach ($blocks as $blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block->getRenderedBlock() === null) {
                $block->setRenderedBlock($this);
            }
            $this->_columnRenders[$blockName] = $block;
        }
        return $this;
    }

    /**
     * Retrieve item renderer block
     *
     * @param string $type
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getItemRenderer($type)
    {
        /** @var $renderer \SM\RefundWithoutReceipt\Block\Adminhtml\Items\AbstractItems */
        $renderer = $this->getChildBlock($type) ? : $this->getChildBlock(self::DEFAULT_TYPE);
        if (!$renderer instanceof BlockInterface) {
            throw new RuntimeException('Renderer for type "' . $type . '" does not exist.');
        }
        $renderer->setColumnRenders($this->getLayout()->getGroupChildNames($this->getNameInLayout(), 'column'));

        return $renderer;
    }

    /**
     * Retrieve column renderer block
     *
     * @param string $column
     * @param string $compositePart
     *
     * @return bool|\Magento\Framework\View\Element\AbstractBlock
     */
    public function getColumnRenderer($column, $compositePart = '')
    {
        $column = 'column_' . $column;
        if (isset($this->_columnRenders[$column . '_' . $compositePart])) {
            $column .= '_' . $compositePart;
        }
        if (!isset($this->_columnRenders[$column])) {
            return false;
        }
        return $this->_columnRenders[$column];
    }

    /**
     * Retrieve rendered item html content
     *
     * @param \Magento\Framework\DataObject $item
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getItemHtml(\Magento\Framework\DataObject $item)
    {
        $type = $item->getProductType();

        return $this->getItemRenderer($type)->setItem($item)->toHtml();
    }

    /**
     * Retrieve rendered column html content
     *
     * @param \Magento\Framework\DataObject $item
     * @param string                        $column the column key
     * @param string                        $field  the custom item field
     *
     * @return string
     */
    public function getColumnHtml(\Magento\Framework\DataObject $item, $column, $field = null)
    {
        $block = $this->getColumnRenderer($column, $item->getProductType());
        if ($block) {
            $block->setItem($item);
            if ($field !== null) {
                $block->setField($field);
            }
            return $block->toHtml();
        }
        return '&nbsp;';
    }

    /**
     * Retrieve available transaction
     *
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTransaction()
    {
        if ($this->coreRegistry->registry('current_refundwr_transaction')) {
            return $this->coreRegistry->registry('current_refundwr_transaction');
        }

        throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t get the transaction instance right now.'));
    }

    /**
     * Retrieve price data object
     *
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPriceDataObject()
    {
        $obj = $this->getData('price_data_object');
        if ($obj === null) {
            return $this->getTransaction();
        }

        return $obj;
    }

    /**
     * Retrieve price attribute html content
     *
     * @param string $code
     * @param bool   $strong
     * @param string $separator
     *
     * @param bool   $isTotal
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function displayPriceAttribute($code, $strong = false, $separator = '<br />')
    {
        $basePrice = $this->getPriceDataObject()->getData('base_' . $code);
        $price = $this->getPriceDataObject()->getData($code);
        return $this->displayPrices(
            $basePrice,
            $price,
            $strong,
            $separator
        );
    }

    /**
     * Retrieve price formatted html content
     *
     * @param float  $basePrice
     * @param float  $price
     * @param bool   $strong
     * @param string $separator
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function displayPrices($basePrice, $price, $strong = false, $separator = '<br />')
    {
        return $this->displayRoundedPrices($basePrice, $price, 2, $strong, $separator);
    }

    /**
     * Display base and regular prices with specified rounding precision
     *
     * @param float  $basePrice
     * @param float  $price
     * @param int    $precision
     * @param bool   $strong
     * @param string $separator
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function displayRoundedPrices($basePrice, $price, $precision = 2, $strong = false, $separator = '<br />')
    {
        if ($this->getTransaction()->isCurrencyDifferent()) {
            $res = '';
            $res .= $this->getTransaction()->formatBasePricePrecision($basePrice, $precision);
            $res .= $separator;
            $res .= $this->getTransaction()->formatPricePrecision($price, $precision, true);
        } else {
            $res = $this->getTransaction()->formatPricePrecision($price, $precision);
            if ($strong) {
                $res = '<strong>' . $res . '</strong>';
            }
        }
        return $res;
    }

    /**
     * Retrieve tax calculation html content
     *
     * @param \Magento\Framework\DataObject $item
     *
     * @return string
     */
    public function displayTaxCalculation(\Magento\Framework\DataObject $item)
    {
        if ($item->getTaxPercent() && $item->getTaxString() == '') {
            $percents = [$item->getTaxPercent()];
        } elseif ($item->getTaxString()) {
            $percents = explode(\Magento\Tax\Model\Config::CALCULATION_STRING_SEPARATOR, (string)$item->getTaxString());
        } else {
            return '0%';
        }

        foreach ($percents as &$percent) {
            $percent = sprintf('%.2f%%', $percent);
        }

        return implode(' + ', $percents);
    }

    /**
     * Retrieve formated price
     *
     * @param float $price
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function formatPrice($price)
    {
        return $this->getTransaction()->formatPrice($price);
    }
}
