<?php
/**
 * Created by Nomad
 * Date: 7/10/19
 * Time: 4:28 PM
 */

namespace SM\RefundWithoutReceipt\Model;


use Exception;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Model\ResourceModel\Metadata;
use SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface;
use SM\RefundWithoutReceipt\Api\RefundWithoutReceiptTransactionRepositoryInterface;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptTransaction as RefundWithoutReceiptTransactionResourceModel;

class RefundWithoutReceiptTransactionRepository implements RefundWithoutReceiptTransactionRepositoryInterface
{

    /**
     * @var \Magento\Sales\Model\ResourceModel\Metadata
     */
    protected $metadata;
    /**
     * @var \Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory
     */
    protected $searchResultFactory;
    /**
     * @var \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransactionFactory
     */
    protected $refundWithoutReceiptTransactionFactory;
    /**
     * @var RefundWithoutReceiptTransactionResourceModel
     */
    protected $resourceModel;

    /**
     * RefundWithoutReceiptTransactionRepository constructor.
     *
     * @param \Magento\Sales\Model\ResourceModel\Metadata                             $metadata
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory               $searchResultFactory
     * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransactionFactory   $refundWithoutReceiptTransactionFactory
     * @param RefundWithoutReceiptTransactionResourceModel                            $resourceModel
     */
    public function __construct(
        Metadata $metadata,
        SearchResultFactory $searchResultFactory,
        RefundWithoutReceiptTransactionFactory $refundWithoutReceiptTransactionFactory,
        RefundWithoutReceiptTransactionResourceModel $resourceModel
    ) {
        $this->metadata            = $metadata;
        $this->searchResultFactory = $searchResultFactory;
        $this->refundWithoutReceiptTransactionFactory = $refundWithoutReceiptTransactionFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @param \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface $transaction
     *
     * @return \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Exception
     */
    public function save(RefundWithoutReceiptTransactionInterface $transaction)
    {
        try {
            $this->resourceModel->save($transaction);
        } catch (AlreadyExistsException $e) {
            throw new AlreadyExistsException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new Exception(__($e->getMessage()));
        }

        return $transaction;
    }

    /**
     * Loads a specified transaction.
     *
     * @param int $id The Transaction ID.
     *
     * @return \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id)
    {
        $transaction = $this->refundWithoutReceiptTransactionFactory->create();
        $this->resourceModel->load($transaction, $id);
        if (! $transaction->getId()) {
            throw new NoSuchEntityException(__('Unable to find refund transaction with ID "%1"', $id));
        }
        return $transaction;
    }

    /**
     * @param \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface $transaction
     *
     * @return void
     * @throws \Exception
     */
    public function delete(RefundWithoutReceiptTransactionInterface $transaction)
    {
        try {
            $this->resourceModel->delete($transaction);
        } catch (Exception $e) {
            throw new Exception(__($e->getMessage()));
        }
    }
}
