<?php
/**
 * Created by Nomad
 * Date: 6/24/19
 * Time: 10:49 AM
 */

namespace SM\RefundWithoutReceipt\Model;


use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptItemInterface;

class RefundWithoutReceiptItem extends AbstractModel implements RefundWithoutReceiptItemInterface, IdentityInterface
{

    /**
     * Cache tag
     */
    const CACHE_TAG = 'sm_refund_without_receipt_item';

    /**
     * Model Initialization
     *
     * @return $this
     */
    protected function _construct()
    {
        $this->_init('SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptItem');
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
     * Get Item ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::ITEM_ID);
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ITEM_ID, $id);
    }

    /**
     * @return int|null
     */
    public function getTransactionId()
    {
        return $this->getData(self::TRANSACTION_ID);
    }

    /**
     * @param $transactionId
     *
     * @return $this
     */
    public function setTransactionId($transactionId)
    {
        return $this->setData(self::TRANSACTION_ID, $transactionId);
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
        return $this->getData(self::SHIFT_ADJUSTMENT_ID);
    }

    /**
     * @param int $shiftId
     *
     * @return $this
     */
    public function setShiftId($shiftId)
    {
        return $this->setData(self::SHIFT_ADJUSTMENT_ID, $shiftId);
    }

    /**
     * @return int|null
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @param int $productId
     *
     * @return $this
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * @return string|null
     */
    public function getProductType()
    {
        return $this->getData(self::PRODUCT_TYPE);
    }

    /**
     * @param string $productType
     *
     * @return $this
     */
    public function setProductType($productType)
    {
        return $this->setData(self::PRODUCT_TYPE, $productType);
    }

    /**
     * @return string|null
     */
    public function getProductOptions()
    {
        return $this->getData(self::PRODUCT_OPTIONS);
    }

    /**
     * @param string $productOptions
     *
     * @return $this
     */
    public function setProductOptions($productOptions)
    {
        return $this->setData(self::PRODUCT_OPTIONS, $productOptions);
    }

    /**
     * @return string|null
     */
    public function getProductSku()
    {
        return $this->getData(self::PRODUCT_SKU);
    }

    /**
     * @param string $productSku
     *
     * @return $this
     */
    public function setProductSku($productSku)
    {
        return $this->setData(self::PRODUCT_SKU, $productSku);
    }

    /**
     * @return string|null
     */
    public function getProductName()
    {
        return $this->getData(self::PRODUCT_NAME);
    }

    /**
     * @param string $productName
     *
     * @return $this
     */
    public function setProductName($productName)
    {
        return $this->setData(self::PRODUCT_NAME, $productName);
    }

    /**
     * @return int|null
     */
    public function getProductQty()
    {
        return $this->getData(self::PRODUCT_QTY);
    }

    /**
     * @param int $productQty
     *
     * @return $this
     */
    public function setProductQty($productQty)
    {
        return $this->setData(self::PRODUCT_QTY, $productQty);
    }

    /**
     * @return float|null
     */
    public function getProductPrice()
    {
        return $this->getData(self::PRODUCT_PRICE);
    }

    /**
     * @param float $productPrice
     *
     * @return $this
     */
    public function setProductPrice($productPrice)
    {
        return $this->setData(self::PRODUCT_PRICE, $productPrice);
    }

    /**
     * @return float|null
     */
    public function getBaseProductPrice()
    {
        return $this->getData(self::BASE_PRODUCT_PRICE);
    }

    /**
     * @param float $baseProductPrice
     *
     * @return $this
     */
    public function setBaseProductPrice($baseProductPrice)
    {
        return $this->setData(self::BASE_PRODUCT_PRICE, $baseProductPrice);
    }

    /**
     * @return float|null
     */
    public function getRowTotal()
    {
        return $this->getData(self::ROW_TOTAL);
    }

    /**
     * @param float $rowTotal
     *
     * @return $this
     */
    public function setRowTotal($rowTotal)
    {
        return $this->setData(self::ROW_TOTAL, $rowTotal);
    }

    /**
     * @return float|null
     */
    public function getBaseRowTotal()
    {
        return $this->getData(self::BASE_ROW_TOTAL);
    }

    /**
     * @param $baseRowTotal
     *
     * @return $this
     */
    public function setBaseRowTotal($baseRowTotal)
    {
        return $this->setData(self::BASE_ROW_TOTAL, $baseRowTotal);
    }

    /**
     * @return float|null
     */
    public function getSubTotal()
    {
        return $this->getData(self::SUB_TOTAL);
    }

    /**
     * @param float $subTotal
     *
     * @return $this
     */
    public function setSubTotal($subTotal)
    {
        return $this->setData(self::SUB_TOTAL, $subTotal);
    }

    /**
     * @return float|null
     */
    public function getBaseSubTotal()
    {
        return $this->getData(self::BASE_SUB_TOTAL);
    }

    /**
     * @param $baseSubTotal
     *
     * @return $this
     */
    public function setBaseSubTotal($baseSubTotal)
    {
        return $this->setData(self::BASE_SUB_TOTAL, $baseSubTotal);
    }

    /**
     * @return string|null
     */
    public function getCustomSalesNote()
    {
        return $this->getData(self::CUSTOM_SALES_NOTE);
    }

    /**
     * @param string $customSalesNote
     *
     * @return $this
     */
    public function setCustomSalesNote($customSalesNote)
    {
        return $this->setData(self::CUSTOM_SALES_NOTE, $customSalesNote);
    }

    /**
     * @return boolean|null
     */
    public function getBackToStock()
    {
        return $this->getData(self::BACK_TO_STOCK);
    }

    /**
     * @param boolean $returnToStock
     *
     * @return $this
     */
    public function setBackToStock($returnToStock)
    {
        return $this->setData(self::BACK_TO_STOCK, $returnToStock);
    }
}
