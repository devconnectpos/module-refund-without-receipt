<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\RefundWithoutReceipt\Block\Adminhtml\Transaction;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 * Adminhtml order totals block
 *
 * @api
 * @author      Magento Core Team <core@magentocommerce.com>
 * @since       100.0.2
 */
class Totals extends Template
{
    /**
     * @var array
     */
    protected $totals;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    public function __construct(
        Template\Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\View\Element\Template
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeToHtml()
    {
        $this->initTotals();
        foreach ($this->getLayout()->getChildBlocks($this->getNameInLayout()) as $child) {
            if (method_exists($child, 'initTotals') && is_callable([$child, 'initTotals'])) {
                $child->initTotals();
            }
        }
        return parent::_beforeToHtml();
    }

    /**
     * Initialize order totals array
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function initTotals()
    {
        $this->totals             = [];
        $this->totals['subtotal'] = new DataObject(
            [
                'code'       => 'subtotal',
                'value'      => $this->getTransaction()->getTotalRefundAmount(),
                'base_value' => $this->getTransaction()->getBaseTotalRefundAmount(),
                'label'      => __('Subtotal'),
            ]
        );


        $this->totals['grand_total'] = new DataObject(
            [
                'code'       => 'grand_total',
                'strong'     => true,
                'value'      => $this->getTransaction()->getTotalRefundAmount(),
                'base_value' => $this->getTransaction()->getBaseTotalRefundAmount(),
                'label'      => __('Grand Total'),
                'area'       => 'footer',
            ]
        );

        $this->totals['refunded'] = new DataObject(
            [
                'code'       => 'refunded',
                'strong'     => true,
                'value'      => $this->getTransaction()->getTotalRefundAmount(),
                'base_value' => $this->getTransaction()->getBaseTotalRefundAmount(),
                'label'      => __('Total Refunded'),
                'area'       => 'footer',
            ]
        );

        return $this;
    }

    /**
     * Retrieve available transaction
     *
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getTransaction()
    {
        if ($this->coreRegistry->registry('current_refundwr_transaction')) {
            return $this->coreRegistry->registry('current_refundwr_transaction');
        }
        throw new LocalizedException(__('We can\'t get the order instance right now.'));
    }

    /**
     * Get Total object by code
     *
     * @param string $code
     * @return mixed
     */
    public function getTotal($code)
    {
        if (isset($this->totals[$code])) {
            return $this->totals[$code];
        }
        return false;
    }

    /**
     * get totals array for visualization
     *
     * @param array|null $area
     * @return array
     */
    public function getTotals($area = null)
    {
        $totals = [];
        if ($area === null) {
            $totals = $this->totals;
        } else {
            $area = (string)$area;
            foreach ($this->totals as $total) {
                $totalArea = (string)$total->getData('area');
                if ($totalArea == $area) {
                    $totals[] = $total;
                }
            }
        }
        return $totals;
    }

    /**
     * Format total value based on order currency
     *
     * @param \Magento\Framework\DataObject $total
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function formatValue($total)
    {
        if (!$total->getIsFormated()) {
            return $this->displayRoundedPrices($total->getBaseValue(), $total->getValue());
        }
        return $total->getValue();
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

}
