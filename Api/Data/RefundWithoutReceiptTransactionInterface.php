<?php
/**
 * Created by Nomad
 * Date: 6/21/19
 * Time: 5:32 PM
 */

namespace SM\RefundWithoutReceipt\Api\Data;

interface RefundWithoutReceiptTransactionInterface
{

    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const TRANSACTION_ID              = 'transaction_id';
    const EXCHANGE_ORDER_ID           = 'exchange_order_id';
    const EXCHANGE_ORDER_INCREMENT_ID = 'exchange_order_increment_id';
    const CUSTOMER_ID                 = 'customer_id';
    const CUSTOMER_GROUP_ID           = 'customer_group_id';
    const CUSTOMER_FIRST_NAME         = 'customer_first_name';
    const CUSTOMER_LAST_NAME          = 'customer_last_name';
    const CUSTOMER_EMAIL              = 'customer_email';
    const CUSTOMER_SHIPPING_ADDRESS   = 'customer_shipping_address';
    const CUSTOMER_BILLING_ADDRESS    = 'customer_billing_address';
    const CUSTOMER_TELEPHONE          = 'customer_telephone';
    const TOTAL_REFUND_AMOUNT         = 'total_refund_amount';
    const BASE_TOTAL_REFUND_AMOUNT    = 'base_total_refund_amount';
    const STORE_ID                    = 'store_id';
    const OUTLET_ID                   = 'outlet_id';
    const REGISTER_ID                 = 'register_id';
    const WAREHOUSE_ID                = 'warehouse_id';
    const USER_ID                     = 'user_id';
    const SELLERS                     = 'sellers';
    const CURRENCY_CODE               = 'currency_code';
    const PAYMENT_DATA                = 'payment_data';
    const CREATED_AT                  = 'created_at';
    const UPDATED_AT                  = 'updated_at';
    const TAX_PERCENT                 = 'tax_percent';
    const TAX_AMOUNT                  = 'tax_amount';
    const BASE_TAX_AMOUNT             = 'base_tax_amount';
    const SUBTOTAL                    = 'subtotal_refund_amount';
    const BASE_SUBTOTAL               = 'base_subtotal_refund_amount';
	const SHIFT_ADJUSTMENT_ID         = 'shift_adjustment_id';
	const SHIFT_ID                    = 'shift_id';
    /**#@-*/

    /**
     * Get Transaction ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @return int|null
     */
    public function getExchangeOrderId();

    /**
     * @param int $exchangeOrderId
     *
     * @return $this
     */
    public function setExchangeOrderId($exchangeOrderId);

    /**
     * @return string|null
     */
    public function getExchangeOrderIncrementId();

    /**
     * @param string $exchangeOrderIncrementId
     *
     * @return $this
     */
    public function setExchangeOrderIncrementId($exchangeOrderIncrementId);

    /**
     * @return int|null
     */
    public function getCustomerId();

    /**
     * @param int $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return int|null
     */
    public function getCustomerGroupId();

    /**
     * @param int $customerGroupId
     *
     * @return $this
     */
    public function setCustomerGroupId($customerGroupId);

    /**
     * @return string|null
     */
    public function getCustomerFirstName();

    /**
     * @param string $customerFirstName
     *
     * @return $this
     */
    public function setCustomerFirstName($customerFirstName);

    /**
     * @return string|null
     */
    public function getCustomerLastName();

    /**
     * @param string $customerLastName
     *
     * @return $this
     */
    public function setCustomerLastName($customerLastName);

    /**
     * @return string|null
     */
    public function getCustomerEmail();

    /**
     * @param string $customerEmail
     *
     * @return $this
     */
    public function setCustomerEmail($customerEmail);

    /**
     * @return string|null
     */
    public function getCustomerShippingAddress();

    /**
     * @param $shippingAddress
     *
     * @return $this
     */
    public function setCustomerShippingAddress($shippingAddress);

    /**
     * @return string|null
     */
    public function getCustomerBillingAddress();

    /**
     * @param $billingAddress
     *
     * @return $this
     */
    public function setCustomerBillingAddress($billingAddress);

    /**
     * @return string|null
     */
    public function getCustomerTelephone();

    /**
     * @param $customerTelephone
     *
     * @return $this
     */
    public function setCustomerTelephone($customerTelephone);

    /**
     * @return float|null
     */
    public function getTotalRefundAmount();

    /**
     * @param float $totalAmount
     *
     * @return $this
     */
    public function setTotalRefundAmount($totalAmount);

    /**
     * @return float|null
     */
    public function getBaseTotalRefundAmount();

    /**
     * @param float $baseTotalAmount
     *
     * @return $this
     */
    public function setBaseTotalRefundAmount($baseTotalAmount);

    /**
     * @return int|null
     */
    public function getStoreId();

    /**
     * @param int $storeId
     *
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * @return int|null
     */
    public function getOutletId();

    /**
     * @param int $outletId
     *
     * @return $this
     */
    public function setOutletId($outletId);

    /**
     * @return int|null
     */
    public function getRegisterId();

    /**
     * @param int $registerId
     *
     * @return $this
     */
    public function setRegisterId($registerId);

    /**
     * @return string|null
     */
    public function getWarehouseId();

    /**
     * @param string $warehouseId
     *
     * @return $this
     */
    public function setWarehouseId($warehouseId);

    /**
     * @return string|null
     */
    public function getUserId();

    /**
     * @param string $userId
     *
     * @return $this
     */
    public function setUserId($userId);

    /**
     * @return string|null
     */
    public function getSellers();

    /**
     * @param $sellers
     *
     * @return $this
     */
    public function setSellers($sellers);

    /**
     * @return string|null
     */
    public function getCurrencyCode();

    /**
     * @param string $currencyCode
     *
     * @return $this
     */
    public function setCurrencyCode($currencyCode);

    /**
     * @return string|null
     */
    public function getPaymentData();

    /**
     * @param $paymentData
     *
     * @return $this
     */
    public function setPaymentData($paymentData);

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
	
	/**
	 * @return float|null
	 */
	public function getTaxPercent();
	
	/**
	 * @param $taxPercent
	 * @return $this
	 */
	public function setTaxPercent($taxPercent);
	
	/**
	 * @return float|null
	 */
	public function getTaxAmount();
	
	/**
	 * @param $taxAmount
	 * @return $this
	 */
	public function setTaxAmount($taxAmount);
	
	/**
	 * @return float|null
	 */
	public function getBaseTaxAmount();
	
	/**
	 * @param $taxAmount
	 * @return $this
	 */
	public function setBaseTaxAmount($taxAmount);
	
	/**
	 * @return float|null
	 */
	public function getSubtotalRefundAmount();
	
	/**
	 * @param $amount
	 * @return $this
	 */
	public function setSubtotalRefundAmount($amount);
	
	/**
	 * @return float|null
	 */
	public function getBaseSubtotalRefundAmount();
	
	/**
	 * @param $amount
	 * @return $this
	 */
	public function setBaseSubtotalRefundAmount($amount);
	
	/**
	 * Get Shift Adjustment Id
	 *
	 * @return int|null
	 */
	public function getShiftAdjustmentId();
	
	/**
	 * @param int $shiftAdjustmentId
	 *
	 * @return $this
	 */
	public function setShiftAdjustmentId($shiftAdjustmentId);
	
	/**
	 * Get Shift Id
	 *
	 * @return int|null
	 */
	public function getShiftId();
	
	/**
	 * @param int $shiftId
	 *
	 * @return $this
	 */
	public function setShiftId($shiftId);
}
