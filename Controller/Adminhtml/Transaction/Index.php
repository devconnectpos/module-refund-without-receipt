<?php
/**
 * Created by Nomad
 * Date: 7/10/19
 * Time: 10:26 AM
 */

namespace SM\RefundWithoutReceipt\Controller\Adminhtml\Transaction;

use Magento\Framework\App\ResponseInterface;
use SM\RefundWithoutReceipt\Controller\Adminhtml\Transaction;

class Index extends Transaction
{

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('Refund Without Receipt'));

        return $resultPage;
    }
}
