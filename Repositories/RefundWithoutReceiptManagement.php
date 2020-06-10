<?php
/**
 * Created by Nomad
 * Date: 6/24/19
 * Time: 11:03 AM
 */

namespace SM\RefundWithoutReceipt\Repositories;

use Exception;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Directory\Model\Currency;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;
use SM\Core\Api\Data\CustomerAddress;
use SM\Core\Api\Data\RefundWithoutReceiptTransaction;
use SM\Integrate\Helper\Data as IntegrateHelper;
use SM\Integrate\Model\WarehouseIntegrateManagement;
use SM\Payment\Model\RetailPayment;
use SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItemFactory;
use SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItemRepository;
use SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransactionFactory;
use SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransactionRepository;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem\CollectionFactory as RefundWithoutReceiptItemCollectionFactory;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptTransaction\CollectionFactory as RefundWithoutReceiptTransactionCollectionFactory;
use SM\Shift\Api\RetailTransactionRepositoryInterface;
use SM\Shift\Model\ResourceModel\Shift\CollectionFactory as ShiftCollectionFactory;
use SM\Shift\Model\RetailTransactionFactory;
use SM\Shift\Model\ShiftInOutFactory as ShiftAdjustmentFactory;
use SM\XRetail\Helper\Data as RetailHelper;
use SM\XRetail\Helper\DataConfig;
use SM\XRetail\Repositories\Contract\ServiceAbstract;

class RefundWithoutReceiptManagement extends ServiceAbstract
{

    /**
     * @var RefundWithoutReceiptTransactionCollectionFactory
     */
    protected $refundWithoutReceiptTransactionCollectionFactory;
    /**
     * @var RefundWithoutReceiptItemCollectionFactory
     */
    protected $refundWithoutReceiptItemCollectionFactory;
    /**
     * @var RefundWithoutReceiptTransactionFactory
     */
    protected $refundWithoutReceiptTransactionFactory;
    /**
     * @var RefundWithoutReceiptItemFactory
     */
    protected $refundWithoutReceiptItemFactory;
    /**
     * @var DateTime
     */
    protected $dateTime;
    /**
     * @var RetailHelper
     */
    protected $retailHelper;
    /**
     * @var ShiftCollectionFactory
     */
    protected $shiftCollectionFactory;
    /**
     * @var ShiftAdjustmentFactory
     */
    protected $shiftAdjustmentFactory;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;
    /**
     * @var IntegrateHelper
     */
    protected $integrateHelper;
    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $stockRegistry;
    /**
     * @var \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransactionRepository
     */
    protected $refundWithoutReceiptTransactionRepository;
    /**
     * @var \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItemRepository
     */
    protected $refundWithoutReceiptItemRepository;
    /** @var \Magento\Framework\ObjectManagerInterface */
    protected $objectManager;

    /**
     * Store Credit factory
     *
     * @var \Magento\CustomerBalance\Model\BalanceFactory
     */
    protected $balanceFactory;
    /**
     * @var \SM\Integrate\Model\WarehouseIntegrateManagement
     */
    protected $warehouseIntegrateManagement;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManagement;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;
	/**
	 * @var RetailTransactionFactory
	 */
	private $retailTransactionFactory;
	/**
	 * @var Currency
	 */
	private $currencyModel;
	/**
	 * @var RetailTransactionRepositoryInterface
	 */
	private $retailTransactionRepository;
	
	
	/**
	 * RefundWithoutReceiptManagement constructor.
	 *
	 * @param RequestInterface $requestInterface
	 * @param DataConfig $dataConfig
	 * @param StoreManagerInterface $storeManager
	 * @param RefundWithoutReceiptTransactionCollectionFactory $refundWithoutReceiptTransactionCollectionFactory
	 * @param RefundWithoutReceiptItemCollectionFactory $refundWithoutReceiptItemCollectionFactory
	 * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransactionFactory $refundWithoutReceiptTransactionFactory
	 * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItemFactory $refundWithoutReceiptItemFactory
	 * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransactionRepository $refundWithoutReceiptTransactionRepository
	 * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItemRepository $refundWithoutReceiptItemRepository
	 * @param DateTime $dateTime
	 * @param RetailHelper $retailHelper
	 * @param ShiftCollectionFactory $shiftCollectionFactory
	 * @param ShiftAdjustmentFactory $shiftAdjustmentFactory
	 * @param \Magento\Catalog\Model\Product $product
	 * @param IntegrateHelper $integrateHelper
	 * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 * @param \SM\Integrate\Model\WarehouseIntegrateManagement $warehouseIntegrateManagement
	 * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	 * @param \Magento\Framework\Event\ManagerInterface $evenManager
	 * @param \Magento\Framework\App\ResourceConnection $resource
	 * @param RetailTransactionFactory $retailTransactionFactory
	 * @param RetailTransactionRepositoryInterface $retailTransactionRepository
	 * @param Currency $currencyModel
	 */
    public function __construct(
        RequestInterface $requestInterface,
        DataConfig $dataConfig,
        StoreManagerInterface $storeManager,
        RefundWithoutReceiptTransactionCollectionFactory $refundWithoutReceiptTransactionCollectionFactory,
        RefundWithoutReceiptItemCollectionFactory $refundWithoutReceiptItemCollectionFactory,
        RefundWithoutReceiptTransactionFactory $refundWithoutReceiptTransactionFactory,
        RefundWithoutReceiptItemFactory $refundWithoutReceiptItemFactory,
        RefundWithoutReceiptTransactionRepository $refundWithoutReceiptTransactionRepository,
        RefundWithoutReceiptItemRepository $refundWithoutReceiptItemRepository,
        DateTime $dateTime,
        RetailHelper $retailHelper,
        ShiftCollectionFactory $shiftCollectionFactory,
        ShiftAdjustmentFactory $shiftAdjustmentFactory,
        Product $product,
        IntegrateHelper $integrateHelper,
        StockRegistryInterface $stockRegistry,
        ObjectManagerInterface $objectManager,
        WarehouseIntegrateManagement $warehouseIntegrateManagement,
        ScopeConfigInterface $scopeConfig,
        ManagerInterface $evenManager,
        ResourceConnection $resource,
        RetailTransactionFactory $retailTransactionFactory,
        RetailTransactionRepositoryInterface $retailTransactionRepository,
        Currency $currencyModel
    ) {
        $this->refundWithoutReceiptTransactionCollectionFactory = $refundWithoutReceiptTransactionCollectionFactory;
        $this->refundWithoutReceiptItemCollectionFactory        = $refundWithoutReceiptItemCollectionFactory;
        $this->refundWithoutReceiptTransactionFactory           = $refundWithoutReceiptTransactionFactory;
        $this->refundWithoutReceiptItemFactory                  = $refundWithoutReceiptItemFactory;
        $this->dateTime                                         = $dateTime;
        $this->retailHelper                                     = $retailHelper;
        $this->shiftCollectionFactory                           = $shiftCollectionFactory;
        $this->shiftAdjustmentFactory                           = $shiftAdjustmentFactory;
        $this->product                                          = $product;
        $this->integrateHelper                                  = $integrateHelper;
        $this->stockRegistry                                    = $stockRegistry;
        $this->refundWithoutReceiptTransactionRepository        = $refundWithoutReceiptTransactionRepository;
        $this->refundWithoutReceiptItemRepository               = $refundWithoutReceiptItemRepository;
        $this->objectManager                                    = $objectManager;
        $this->warehouseIntegrateManagement                     = $warehouseIntegrateManagement;
        $this->scopeConfig                                      = $scopeConfig;
        $this->eventManagement                                  = $evenManager;
        $this->resource                                         = $resource;
        $this->connection                                       = $resource->getConnection();
	    $this->retailTransactionFactory                         = $retailTransactionFactory;
	    $this->retailTransactionRepository                      = $retailTransactionRepository;
	    $this->storeManager                                     = $storeManager;
	    $this->currencyModel                                    = $currencyModel;
	
	    parent::__construct($requestInterface, $dataConfig, $storeManager);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function saveTransaction()
    {
        $data              = $this->getRequest()->getParams();
        $results           = [];
        $payment           = null;
	    $refundItems       = $data['items'];
        // only 1 payment allowed when refund, so we get the first one.
        if (isset($data['order']['payment_data'])
            && is_array($data['order']['payment_data'])
            && count($data['order']['payment_data']) > 0) {
            $payment = $data['order']['payment_data'][0];
        }
	    
	    $refundTransaction = $this->createRefundTransaction();
	
	    if ($payment !== null && $payment['type'] !== RetailPayment::REFUND_TO_STORE_CREDIT_PAYMENT_TYPE) {
		    $this->createRetailTransaction($payment, $refundTransaction);
	    }
        
        if ($refundTransactionId = $refundTransaction->getId()) {
            $refundedItems = [];
            foreach ($refundItems as $item) {
                $item['shift_adjustment_id'] = null;
                
                $refundItem   = $this->createRefundItemRecord($item, $refundTransactionId);
                $refundedItem = new RefundWithoutReceiptTransaction\RefundedItem();
                $refundedItem->addData($refundItem->getData());
                if ($item['buy_request']['return_to_stock'] && !isset($item['buy_request']['custom_sale'])) {
                    $this->returnItemToStock($refundItem, $refundTransaction);
                }
                $refundedItems[] = $refundedItem->getOutput();
            }
            $result = new RefundWithoutReceiptTransaction();
            $result->addData($refundTransaction->getData());
            $result->setData('refunded_items', $refundedItems);

            $customerAddress = new CustomerAddress($data['order']['shipping_address']);
            $result->setData('shipping_address', $customerAddress);

            $results[] = $result;
            // add store credit transaction
            if ($payment !== null && $payment['type'] === RetailPayment::REFUND_TO_STORE_CREDIT_PAYMENT_TYPE) {
                $websiteId = $this->storeManager->getStore($data['store_id'])->getWebsiteId();
                $this->getStoreCreditFactory()->create()
                     ->setCustomerId($data['customer']['id'])
                     ->setWebsiteId($websiteId)
                     ->setAmountDelta($data['base_total_refund_amount'])
                     ->setComment(__('Refund without receipt transaction #%1', $refundTransactionId))
                     ->save();
            }
        }

        return $this->getSearchResult()
                    ->setItems($results)
                    ->getOutput();
    }

    /**
     * @param $outletId
     * @param $registerId
     *
     * @return int
     */
    protected function getOpenShiftId($outletId, $registerId)
    {
        /** @var \SM\Shift\Model\ResourceModel\Shift\Collection $collection */
        $collection = $this->shiftCollectionFactory->create();
        $collection->addFieldToFilter('outlet_id', $outletId)
                   ->addFieldToFilter('register_id', $registerId)
                   ->addFieldToFilter('is_open', 1);
        $openShift = $collection->getFirstItem();

        return $openShift->getId();
    }


    /**
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction
     * @throws \Exception
     */
    protected function createRefundTransaction()
    {
        $data              = $this->getRequest()->getParams();
        $customer          = $data['customer'];
        $created_at        = $this->retailHelper->getCurrentTime();
        $sellerIds         = $this->getRequest()->getParam('sellers');
		/** @var \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction $refundTransaction */
	    $refundTransaction = $this->refundWithoutReceiptTransactionFactory->create();
        try {
            $refundTransaction->setCustomerId($customer['id'])
                              ->setCustomerGroupId($customer['customer_group_id'])
                              ->setCustomerFirstName($customer['first_name'])
                              ->setCustomerLastName($customer['last_name'])
                              ->setCustomerEmail($customer['email'])
                              ->setCustomerShippingAddress(json_encode($data['order']['shipping_address']))
                              ->setCustomerBillingAddress(json_encode($data['order']['billing_address']))
                              ->setCustomerTelephone($customer['telephone'])
                              ->setTotalRefundAmount($data['total_refund_amount'])
                              ->setBaseTotalRefundAmount($data['base_total_refund_amount'])
                              ->setSubtotalRefundAmount($data['subtotal_refund_amount'])
                              ->setBaseSubtotalRefundAmount($data['base_subtotal_refund_amount'])
                              ->setStoreId($data['store_id'])
                              ->setOutletId($data['outlet_id'])
                              ->setRegisterId($data['register_id'])
                              ->setUserId($data['user_id'])
                              ->setSellers(implode(",", $sellerIds))
                              ->setCurrencyCode($data['currency_code'])
                              ->setPaymentData(json_encode($data['order']['payment_data']))
                              ->setCreatedAt($created_at)
                              ->setUpdatedAt($created_at);
            if (isset($data['warehouse_id']) && $data['warehouse_id'] !== null) {
                $refundTransaction->setWarehouseId($data['warehouse_id']);
            }
            if (isset($data['refundTaxPercent'])) {
            	$refundTransaction->setTaxPercent($data['refundTaxPercent']);
            }
	        if (isset($data['refundTaxAmount'])) {
		        $refundTransaction->setTaxAmount($data['refundTaxAmount']);
	        }
	        if (isset($data['baseRefundTaxAmount'])) {
		        $refundTransaction->setBaseTaxAmount($data['baseRefundTaxAmount']);
	        }
            $this->refundWithoutReceiptTransactionRepository->save($refundTransaction);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $refundTransaction;
    }

    /**
     * @param $item
     * @param $refundTransactionId
     *
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItem
     * @throws \Exception
     */
    protected function createRefundItemRecord($item, $refundTransactionId)
    {
        $refundItem = $this->refundWithoutReceiptItemFactory->create();
        $product    = $item['product'];
        $outletId   = $this->getRequest()->getParam('outlet_id');
        $registerId = $this->getRequest()->getParam('register_id');
        try {
            $refundItem->setTransactionId($refundTransactionId)
                       ->setShiftAdjustmentId($item['shift_adjustment_id'])
                       ->setShiftId($this->getOpenShiftId($outletId, $registerId))
                       ->setProductId($product['id'])
                       ->setProductType($product['type_id'])
                       ->setProductSku($product['sku'])
                       ->setProductName($product['name'])
                       ->setProductQty($item['qty'])
                       ->setProductOptions(json_encode($item['product_options']))
                       ->setProductPrice($item['price'])
                       ->setBaseProductPrice($item['base_price'])
                       ->setSubTotal($item['sub_total'])
                       ->setBaseSubTotal($item['base_sub_total'])
                       ->setRowTotal($item['row_total'])
                       ->setBaseRowTotal($item['base_row_total'])
                       ->setBackToStock($item['buy_request']['return_to_stock']);

            if (isset($item['buy_request']['custom_sale'])) {
                $refundItem->setCustomSalesNote($item['buy_request']['custom_sale']['note']);
                $refundItem->setProductName($item['buy_request']['custom_sale']['name']);
            }
            $this->refundWithoutReceiptItemRepository->save($refundItem);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $refundItem;
    }

    /**
     * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptItem        $item
     *
     * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction $transaction
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function returnItemToStock($item, $transaction)
    {
        if (!!$this->integrateHelper->isIntegrateMultipleWareHouse()) {
            $warehouseIntegrateModule = $this->warehouseIntegrateManagement->getCurrentIntegrateModel();
            if (!!$warehouseIntegrateModule) {
                $warehouseIntegrateModule->returnItemToStock($item, $transaction);
            }
        } else {
            $stockItem = $this->stockRegistry->getStockItemBySku($item->getProductSku());
            $qty       = (int)$stockItem->getQty() + (int)$item->getProductQty();
            $stockItem->setQty($qty);
            $this->stockRegistry->updateStockItemBySku($item->getProductSku(), $stockItem);
        }
    }

    /**
     * @return \Magento\CustomerBalance\Model\BalanceFactory|mixed
     */
    protected function getStoreCreditFactory()
    {
        if (is_null($this->balanceFactory)) {
            $this->balanceFactory = $this->objectManager->get('Magento\CustomerBalance\Model\BalanceFactory');
        }

        return $this->balanceFactory;
    }
	
	protected function createRetailTransaction($paymentData, \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction $refundTransaction)
	{
		$data = $this->getRequestData();
		
		$baseCurrencyCode    = $this->storeManager->getStore()->getBaseCurrencyCode();
		$currentCurrencyCode = $this->storeManager->getStore($data->getData('store_id'))->getCurrentCurrencyCode();
		$allowedCurrencies   = $this->currencyModel->getConfigAllowCurrencies();
		$rates               = $this->currencyModel->getCurrencyRates($baseCurrencyCode, array_values($allowedCurrencies));
		
		$outletId   = $data->getData('outlet_id');
		$registerId   = $data->getData('register_id');
		
		$baseAmount = $paymentData['amount'];
		if (isset($rates[$currentCurrencyCode]) && $rates[$currentCurrencyCode] != 0) {
			$baseAmount = $paymentData['amount'] / $rates[$currentCurrencyCode];
		}
		
		$created_at = $this->retailHelper->getCurrentTime();
		$retailTransaction         = $this->retailTransactionFactory->create();
		$retailTransaction->addData(
			[
				'outlet_id'     => $outletId,
				'register_id'   => $registerId,
				'shift_id'      => $this->getOpenShiftId($outletId, $registerId),
				'payment_id'    => $paymentData['id'],
				'payment_title' => $paymentData['title'],
				'payment_type'  => $paymentData['type'],
				'amount'        => $paymentData['amount'],
				'is_purchase'   => 0,
				"created_at"    => $created_at,
				'order_id'      => null,
				"user_name"     => isset($data['user_name']) ? $data['user_name'] : '',
				'base_amount'   => $baseAmount,
				'rwr_transaction_id' => $refundTransaction->getId()
			]
		);
		$this->retailTransactionRepository->save($retailTransaction);
	}
}
