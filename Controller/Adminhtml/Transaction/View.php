<?php
/**
 * Created by Nomad
 * Date: 7/11/19
 * Time: 2:00 PM
 */

namespace SM\RefundWithoutReceipt\Controller\Adminhtml\Transaction;


use Exception;
use Magento\Framework\App\ResponseInterface;
use SM\RefundWithoutReceipt\Controller\Adminhtml\Transaction;

class View extends Transaction
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
        $transaction = $this->initTransaction();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($transaction) {
            try {
                $resultPage = $this->_initAction();
                $resultPage->getConfig()->getTitle()->prepend(__('Refund Without Receipt'));
            } catch (Exception $e) {
                $this->logger->critical($e);
                $this->messageManager->addErrorMessage(__('Exception occurred during order load'));
                $resultRedirect->setPath('smrefundwr/transaction/index');
                return $resultRedirect;
            }
            $resultPage->getConfig()->getTitle()->prepend(sprintf("#%s", $transaction->getId()));
            return $resultPage;
        }
        $resultRedirect->setPath('smrefundwr/transaction/index');
        return $resultRedirect;
    }
}
