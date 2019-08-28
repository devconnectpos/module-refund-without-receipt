<?php
/**
 * Created by Nomad
 * Date: 7/10/19
 * Time: 10:26 AM
 */

namespace SM\RefundWithoutReceipt\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use SM\RefundWithoutReceipt\Api\RefundWithoutReceiptTransactionRepositoryInterface;

abstract class Transaction extends Action
{

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ACTION_RESOURCE = 'SM_RefundWithoutReceipt::transaction';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Result Page Factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Result Forward Factory
     *
     * @var ForwardFactory
     */
    protected $resultForwardFactory;
    /**
     * @var \SM\RefundWithoutReceipt\Api\RefundWithoutReceiptTransactionRepositoryInterface
     */
    protected $refundWithoutReceiptTransactionRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Data constructor.
     *
     * @param Registry                                                                        $registry
     * @param PageFactory                                                                     $resultPageFactory
     * @param ForwardFactory                                                                  $resultForwardFactory
     * @param Context                                                                         $context
     * @param \SM\RefundWithoutReceipt\Api\RefundWithoutReceiptTransactionRepositoryInterface $refundWithoutReceiptTransactionRepository
     * @param \Psr\Log\LoggerInterface                                                        $logger
     */
    public function __construct(
        Registry $registry,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Context $context,
        RefundWithoutReceiptTransactionRepositoryInterface $refundWithoutReceiptTransactionRepository,
        LoggerInterface $logger
    ) {
        $this->coreRegistry                              = $registry;
        $this->resultPageFactory                         = $resultPageFactory;
        $this->resultForwardFactory                      = $resultForwardFactory;
        $this->refundWithoutReceiptTransactionRepository = $refundWithoutReceiptTransactionRepository;
        $this->logger                                    = $logger;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SM_RefundWithoutReceipt::transaction');
        $resultPage->addBreadcrumb(__('Sales'), __('Sales'));
        $resultPage->addBreadcrumb(__('Refund Without Receipt'), __('Refund Without Receipt'));

        return $resultPage;
    }

    protected function initTransaction()
    {
        $transaction_id = $this->getRequest()->getParam('transaction_id');
        try {
            $transaction = $this->refundWithoutReceiptTransactionRepository->get($transaction_id);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('This order no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        } catch (InputException $e) {
            $this->messageManager->addErrorMessage(__('This order no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);

            return false;
        }
        $this->coreRegistry->register('current_refundwr_transaction', $transaction);

        return $transaction;
    }
}
