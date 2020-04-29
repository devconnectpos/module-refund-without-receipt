<?php
/**
 * Created by Nomad
 * Date: 6/21/19
 * Time: 5:38 PM
 */

namespace SM\RefundWithoutReceipt\Model;


use Magento\Directory\Model\Currency;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use SM\Customer\Helper\Data;
use SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface;
use SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem\CollectionFactory as RefundWithoutReceiptItemCollectionFactory;

class RefundWithoutReceiptTransaction extends AbstractModel implements RefundWithoutReceiptTransactionInterface, IdentityInterface
{

    /**
     * Cache tag
     */
    const CACHE_TAG = 'sm_refund_without_receipt_transaction';
    /**
     * @var \SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem\CollectionFactory
     */
    protected $refundWithoutReceiptItemCollectionFactory;
    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $currencyFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    private $transactionCurrency;
    /**
     * @var \Magento\Directory\Model\Currency
     */
    private $baseCurrency;

    /**
     * RefundWithoutReceiptTransaction constructor.
     *
     * @param \Magento\Framework\Model\Context                                                        $context
     * @param \Magento\Framework\Registry                                                             $registry
     * @param \SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem\CollectionFactory $refundWithoutReceiptItemCollectionFactory
     * @param \Magento\Directory\Model\CurrencyFactory                                                $currencyFactory
     * @param \Magento\Store\Model\StoreManagerInterface                                              $storeManager
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null                            $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null                                      $resourceCollection
     * @param array                                                                                   $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        RefundWithoutReceiptItemCollectionFactory $refundWithoutReceiptItemCollectionFactory,
        CurrencyFactory $currencyFactory,
        StoreManagerInterface $storeManager,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->refundWithoutReceiptItemCollectionFactory = $refundWithoutReceiptItemCollectionFactory;
        $this->currencyFactory                           = $currencyFactory;
        $this->storeManager                              = $storeManager;
    }

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptTransaction');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get Transaction ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::TRANSACTION_ID);
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::TRANSACTION_ID, $id);
    }

    /**
     * @return int|null
     */
    public function getExchangeOrderId()
    {
        return $this->getData(self::EXCHANGE_ORDER_ID);
    }

    /**
     * @param int $exchangeOrderId
     *
     * @return $this
     */
    public function setExchangeOrderId($exchangeOrderId)
    {
        return $this->setData(self::EXCHANGE_ORDER_ID, $exchangeOrderId);
    }

    /**
     * @return string|null
     */
    public function getExchangeOrderIncrementId()
    {
        return $this->getData(self::EXCHANGE_ORDER_INCREMENT_ID);
    }

    /**
     * @param string $exchangeOrderIncrementId
     *
     * @return $this
     */
    public function setExchangeOrderIncrementId($exchangeOrderIncrementId)
    {
        return $this->setData(self::EXCHANGE_ORDER_INCREMENT_ID, $exchangeOrderIncrementId);
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @param int $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * @return string|null
     */
    public function getCustomerFirstName()
    {
        return $this->getData(self::CUSTOMER_FIRST_NAME);
    }

    /**
     * @param string $customerFirstName
     *
     * @return $this
     */
    public function setCustomerFirstName($customerFirstName)
    {
        return $this->setData(self::CUSTOMER_FIRST_NAME, $customerFirstName);
    }

    /**
     * @return string|null
     */
    public function getCustomerLastName()
    {
        return $this->getData(self::CUSTOMER_LAST_NAME);
    }

    /**
     * @param string $customerLastName
     *
     * @return $this
     */
    public function setCustomerLastName($customerLastName)
    {
        return $this->setData(self::CUSTOMER_LAST_NAME, $customerLastName);
    }

    /**
     * @return string|null
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * @param string $customerEmail
     *
     * @return $this
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * @return string|null
     */
    public function getCustomerShippingAddress()
    {
        return $this->getData(self::CUSTOMER_SHIPPING_ADDRESS);
    }

    /**
     * @param $shippingAddress
     *
     * @return $this
     */
    public function setCustomerShippingAddress($shippingAddress)
    {
        return $this->setData(self::CUSTOMER_SHIPPING_ADDRESS, $shippingAddress);
    }

    /**
     * @return string|null
     */
    public function getCustomerBillingAddress()
    {
        return $this->getData(self::CUSTOMER_BILLING_ADDRESS);
    }

    /**
     * @param $billingAddress
     *
     * @return $this
     */
    public function setCustomerBillingAddress($billingAddress)
    {
        return $this->setData(self::CUSTOMER_BILLING_ADDRESS, $billingAddress);
    }

    /**
     * @return string|null
     */
    public function getCustomerTelephone()
    {
        return $this->getData(self::CUSTOMER_TELEPHONE);
    }

    /**
     * @param $customerTelephone
     *
     * @return $this
     */
    public function setCustomerTelephone($customerTelephone)
    {
        return $this->setData(self::CUSTOMER_TELEPHONE, $customerTelephone);
    }

    /**
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return float|null
     */
    public function getTotalRefundAmount()
    {
        return $this->getData(self::TOTAL_REFUND_AMOUNT);
    }

    /**
     * @param float $totalAmount
     *
     * @return $this
     */
    public function setTotalRefundAmount($totalAmount)
    {
        return $this->setData(self::TOTAL_REFUND_AMOUNT, $totalAmount);
    }

    /**
     * @return float|null
     */
    public function getBaseTotalRefundAmount()
    {
        return $this->getData(self::BASE_TOTAL_REFUND_AMOUNT);
    }

    /**
     * @param float $baseTotalAmount
     *
     * @return $this
     */
    public function setBaseTotalRefundAmount($baseTotalAmount)
    {
        return $this->setData(self::BASE_TOTAL_REFUND_AMOUNT, $baseTotalAmount);
    }

    /**
     * @return int|null
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @param int $storeId
     *
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @return int|null
     */
    public function getOutletId()
    {
        return $this->getData(self::OUTLET_ID);
    }

    /**
     * @param int $outletId
     *
     * @return $this
     */
    public function setOutletId($outletId)
    {
        return $this->setData(self::OUTLET_ID, $outletId);
    }

    /**
     * @return int|null
     */
    public function getRegisterId()
    {
        return $this->getData(self::REGISTER_ID);
    }

    /**
     * @param int $registerId
     *
     * @return $this
     */
    public function setRegisterId($registerId)
    {
        return $this->setData(self::REGISTER_ID, $registerId);
    }

    /**
     * @return string|null
     */
    public function getWarehouseId()
    {
        return $this->getData(self::WAREHOUSE_ID);
    }

    /**
     * @param string $warehouseId
     *
     * @return $this
     */
    public function setWarehouseId($warehouseId)
    {
        return $this->setData(self::WAREHOUSE_ID, $warehouseId);
    }

    /**
     * @return string|null
     */
    public function getUserId()
    {
        return $this->getData(self::USER_ID);
    }

    /**
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }

    /**
     * @return string|null
     */
    public function getSellers()
    {
        return $this->getData(self::SELLERS);
    }

    /**
     * @param $sellers
     *
     * @return $this
     */
    public function setSellers($sellers)
    {
        return $this->setData(self::SELLERS, $sellers);
    }

    /**
     * @return \SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem\Collection
     */
    public function getItemsCollection()
    {
        return $this->refundWithoutReceiptItemCollectionFactory->create()->addFieldToFilter('transaction_id', ['eq' => $this->getId()]);
    }

    public function getCustomerIsGuest()
    {
        return $this->getCustomerEmail() === Data::DEFAULT_CUSTOMER_RETAIL_EMAIL;
    }

    /**
     * @return int|null
     */
    public function getCustomerGroupId()
    {
        return $this->getData(self::CUSTOMER_GROUP_ID);
    }

    /**
     * @param int $customerGroupId
     *
     * @return $this
     */
    public function setCustomerGroupId($customerGroupId)
    {
        return $this->setData(self::CUSTOMER_GROUP_ID, $customerGroupId);
    }

    /**
     * @return string|null
     */
    public function getCurrencyCode()
    {
        return $this->getData(self::CURRENCY_CODE);
    }

    /**
     * @param string $currencyCode
     *
     * @return $this
     */
    public function setCurrencyCode($currencyCode)
    {
        return $this->setData(self::CURRENCY_CODE, $currencyCode);
    }

    /**
     * @return string|null
     */
    public function getPaymentData()
    {
        return $this->getData(self::PAYMENT_DATA);
    }

    /**
     * @param $paymentData
     *
     * @return $this
     */
    public function setPaymentData($paymentData)
    {
        return $this->setData(self::PAYMENT_DATA, $paymentData);
    }

    /**
     * Get currency model instance.
     *
     * @return Currency
     */
    public function getTransactionCurrency()
    {
        if ($this->transactionCurrency === null) {
            $this->transactionCurrency = $this->currencyFactory->create();
            $this->transactionCurrency->load($this->getCurrencyCode());
        }

        return $this->transactionCurrency;
    }

    /**
     * Retrieve order website currency for working with base prices
     *
     * @return Currency
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseCurrency()
    {
        if ($this->baseCurrency === null) {
            $this->baseCurrency = $this->currencyFactory->create()->load($this->getBaseCurrencyCode());
        }

        return $this->baseCurrency;
    }

    /**
     * Get formatted price value including currency rate to website currency
     *
     * @param float $price
     * @param bool  $addBrackets
     *
     * @param bool  $includeContainer
     *
     * @return string
     */
    public function formatPrice($price, $addBrackets = false, $includeContainer = true)
    {
        return $this->formatPricePrecision($price, 2, $addBrackets, $includeContainer);
    }

    /**
     * Format price precision.
     *
     * @param float $price
     * @param int   $precision
     * @param bool  $addBrackets
     *
     * @param bool  $includeContainer
     *
     * @return string
     */
    public function formatPricePrecision($price, $precision, $addBrackets = false, $includeContainer = true)
    {
        return $this->getTransactionCurrency()->formatPrecision($price, $precision, [], $includeContainer, $addBrackets);
    }

    /**
     * Format price precision.
     *
     * @param float $price
     * @param int   $precision
     * @param bool  $addBrackets
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function formatBasePricePrecision($price, $precision)
    {
        return $this->getBaseCurrency()->formatPrecision($price, $precision);
    }

    /**
     * Retrieve store model instance
     *
     * @return \Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStore()
    {
        $storeId = $this->getStoreId();
        if ($storeId) {
            return $this->storeManager->getStore($storeId);
        }

        return $this->storeManager->getStore();
    }

    /**
     * Get customer name.
     *
     * @return string
     */
    public function getCustomerName()
    {
        if ($this->getCustomerFirstname()) {
            $customerName = $this->getCustomerFirstname() . ' ' . $this->getCustomerLastname();
        } else {
            $customerName = (string)__('Guest');
        }

        return $customerName;
    }

    /**
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseCurrencyCode()
    {
        return $this->storeManager->getStore()->getBaseCurrencyCode();
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isCurrencyDifferent()
    {
        return $this->getCurrencyCode() != $this->getBaseCurrencyCode();
    }
	
	/**
	 * @inheritDoc
	 */
	public function getTaxPercent()
	{
		return $this->getData(self::TAX_PERCENT);
	}
	
	/**
	 * @inheritDoc
	 */
	public function setTaxPercent($taxPercent)
	{
		return $this->setData(self::TAX_PERCENT, $taxPercent);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getTaxAmount()
	{
		return $this->getData(self::TAX_AMOUNT);
	}
	
	/**
	 * @inheritDoc
	 */
	public function setTaxAmount($taxAmount)
	{
		return $this->setData(self::TAX_AMOUNT, $taxAmount);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getBaseTaxAmount()
	{
		return $this->getData(self::BASE_TAX_AMOUNT);
	}
	
	/**
	 * @inheritDoc
	 */
	public function setBaseTaxAmount($taxAmount)
	{
		return $this->setData(self::BASE_TAX_AMOUNT, $taxAmount);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getSubtotalRefundAmount()
	{
		return $this->getData(self::SUBTOTAL);
	}
	
	/**
	 * @inheritDoc
	 */
	public function setSubtotalRefundAmount($amount)
	{
		return $this->setData(self::SUBTOTAL, $amount);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getBaseSubtotalRefundAmount()
	{
		return $this->getData(self::BASE_SUBTOTAL);
	}
	
	/**
	 * @inheritDoc
	 */
	public function setBaseSubtotalRefundAmount($amount)
	{
		return $this->setData(self::BASE_SUBTOTAL, $amount);
	}
	
	/**
	 * Get Shift Adjustment Id
	 *
	 * @return int|null
	 */
	public function getShiftAdjustmentId()
	{
		return $this->getData(self::SHIFT_ADJUSTMENT_ID);
	}
	
	/**
	 * @param int $shiftAdjustmentId
	 *
	 * @return $this
	 */
	public function setShiftAdjustmentId($shiftAdjustmentId)
	{
		return $this->setData(self::SHIFT_ADJUSTMENT_ID, $shiftAdjustmentId);
	}
	
	/**
	 * Get Shift Id
	 *
	 * @return int|null
	 */
	public function getShiftId()
	{
		return $this->getData(self::SHIFT_ID);
	}
	
	/**
	 * @param int $shiftId
	 *
	 * @return $this
	 */
	public function setShiftId($shiftId)
	{
		return $this->setData(self::SHIFT_ID, $shiftId);
	}
}
