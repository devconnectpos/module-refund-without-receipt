<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\RefundWithoutReceipt\Block\Adminhtml\Transaction;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

class AbstractTransaction extends Widget
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param array                                   $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
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
        throw new LocalizedException(__('We can\'t get the order instance right now.'));
    }

    /**
     * Retrieve transaction totals block settings
     *
     * @return array
     */
    public function getTransactionTotalData()
    {
        return [];
    }

    /**
     * Retrieve transaction info block settings
     *
     * @return array
     */
    public function getTransactionInfoData()
    {
        return [];
    }
}
