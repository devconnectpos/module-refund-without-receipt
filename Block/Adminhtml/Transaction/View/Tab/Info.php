<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\RefundWithoutReceipt\Block\Adminhtml\Transaction\View\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;
use SM\RefundWithoutReceipt\Block\Adminhtml\Transaction\AbstractTransaction;

class Info extends AbstractTransaction implements TabInterface
{
    /**
     * Retrieve transaction model instance
     *
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction
     */
    public function getTransaction()
    {
        return $this->coreRegistry->registry('current_refundwr_transaction');
    }

    /**
     * Retrieve source model instance
     *
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction
     */
    public function getSource()
    {
        return $this->getTransaction();
    }

    /**
     * Get items html
     *
     * @return string
     */
    public function getItemsHtml()
    {
        return $this->getChildHtml('transaction_items');
    }

    /**
     * Get payment html
     *
     * @return string
     */
    public function getPayment()
    {
        return json_decode($this->getTransaction()->getPaymentData(), true);
    }

    /**
     * Get totals html
     * @return string
     */
    public function getTotalsHtml() {
        return $this->getChildHtml('transaction_totals');
    }

    /**
     * ######################## TAB settings #################################
     */

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Refund Without Receipt Transaction');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
