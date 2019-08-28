<?php
/**
 * Created by Nomad
 * Date: 6/24/19
 * Time: 10:26 AM
 */

namespace SM\RefundWithoutReceipt\Api\Data;


interface RefundWithoutReceiptItemInterface
{

    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ITEM_ID             = 'item_d';
    const TRANSACTION_ID      = 'transaction_id';
    const SHIFT_ADJUSTMENT_ID = 'shift_adjustment_id';
    const PRODUCT_ID          = 'product_id';
    const PRODUCT_TYPE        = 'product_type';
    const PRODUCT_OPTIONS     = 'product_options';
    const PRODUCT_SKU         = 'product_sku';
    const PRODUCT_NAME        = 'product_name';
    const PRODUCT_QTY         = 'product_qty';
    const PRODUCT_PRICE       = 'product_price';
    const BASE_PRODUCT_PRICE  = 'base_product_price';
    const ROW_TOTAL           = 'row_total';
    const BASE_ROW_TOTAL      = 'base_row_total';
    const SUB_TOTAL           = 'sub_total';
    const BASE_SUB_TOTAL      = 'base_sub_total';
    const CUSTOM_SALES_NOTE   = 'custom_sales_note';
    const BACK_TO_STOCK       = 'back_to_stock';

    /**#@-*/

    /**
     * Get Item ID
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
    public function getTransactionId();

    /**
     * @param $transactionId
     *
     * @return $this
     */
    public function setTransactionId($transactionId);

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

    /**
     * @return int|null
     */
    public function getProductId();

    /**
     * @param int $productId
     *
     * @return $this
     */
    public function setProductId($productId);

    /**
     * @return string|null
     */
    public function getProductType();

    /**
     * @param string $productType
     *
     * @return $this
     */
    public function setProductType($productType);

    /**
     * @return string|null
     */
    public function getProductOptions();

    /**
     * @param string $productOptions
     *
     * @return $this
     */
    public function setProductOptions($productOptions);

    /**
     * @return string|null
     */
    public function getProductSku();

    /**
     * @param string $productSku
     *
     * @return $this
     */
    public function setProductSku($productSku);

    /**
     * @return string|null
     */
    public function getProductName();

    /**
     * @param string $productName
     *
     * @return $this
     */
    public function setProductName($productName);

    /**
     * @return int|null
     */
    public function getProductQty();

    /**
     * @param int $productQty
     *
     * @return $this
     */
    public function setProductQty($productQty);

    /**
     * @return float|null
     */
    public function getProductPrice();

    /**
     * @param float $productPrice
     *
     * @return $this
     */
    public function setProductPrice($productPrice);

    /**
     * @return float|null
     */
    public function getBaseProductPrice();

    /**
     * @param float $baseProductPrice
     *
     * @return $this
     */
    public function setBaseProductPrice($baseProductPrice);

    /**
     * @return float|null
     */
    public function getRowTotal();

    /**
     * @param float $rowTotal
     *
     * @return $this
     */
    public function setRowTotal($rowTotal);

    /**
     * @return float|null
     */
    public function getBaseRowTotal();

    /**
     * @param $baseRowTotal
     *
     * @return $this
     */
    public function setBaseRowTotal($baseRowTotal);

    /**
     * @return float|null
     */
    public function getSubTotal();

    /**
     * @param float $subTotal
     *
     * @return $this
     */
    public function setSubTotal($subTotal);

    /**
     * @return float|null
     */
    public function getBaseSubTotal();

    /**
     * @param $baseSubTotal
     *
     * @return $this
     */
    public function setBaseSubTotal($baseSubTotal);

    /**
     * @return string|null
     */
    public function getCustomSalesNote();

    /**
     * @param string $customSalesNote
     *
     * @return $this
     */
    public function setCustomSalesNote($customSalesNote);

    /**
     * @return boolean|null
     */
    public function getBackToStock();

    /**
     * @param boolean $returnToStock
     *
     * @return $this
     */
    public function setBackToStock($returnToStock);
}
