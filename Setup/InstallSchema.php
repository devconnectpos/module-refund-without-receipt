<?php
/**
 * Created by Nomad
 * Date: 6/20/19
 * Time: 4:26 PM
 */

namespace SM\RefundWithoutReceipt\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $this->createRefundWithoutReceiptTransactionTable($installer);
        $this->createRefundWithoutReceiptItemTable($installer);
        $installer->endSetup();
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     *
     * @throws \Zend_Db_Exception
     */
    protected function createRefundWithoutReceiptTransactionTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('sm_refund_without_receipt_transaction');
        if (!$setup->getConnection()->isTableExists($tableName)) {
            $table = $setup->getConnection()->newTable($tableName);
            $table->addColumn(
                'transaction_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'  => true
                ],
                'Transaction ID'
            )->addColumn(
                'exchange_order_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Exchange Order Id'
            )->addColumn(
                'exchange_order_increment_id',
                Table::TYPE_TEXT,
                20,
                ['nullable' => true],
                'Exchange Order Increment Id'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Customer Id'
            )->addColumn(
                'customer_group_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Customer Group Id'
            )->addColumn(
                'customer_first_name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Customer First Name'
            )->addColumn(
                'customer_last_name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Customer Last Name'
            )->addColumn(
                'customer_email',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Customer Email'
            )->addColumn(
                'customer_shipping_address',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Customer Shipping Address'
            )->addColumn(
                'customer_billing_address',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Customer Billing Address'
            )->addColumn(
                'customer_telephone',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Customer Telephone'
            )->addColumn(
                'total_refund_amount',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => true],
                'Total Refund Amount'
            )->addColumn(
                'base_total_refund_amount',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => true],
                'Base Total Refund Amount'
            )->addColumn(
                'store_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Store Id'
            )->addColumn(
                'outlet_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Outlet Id'
            )->addColumn(
                'register_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Register Id'
            )->addColumn(
                'warehouse_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Warehouse Id'
            )->addColumn(
                'user_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'User Id'
            )->addColumn(
                'sellers',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Sellers'
            )->addColumn(
                'currency_code',
                Table::TYPE_TEXT,
                '3',
                ['nullable' => true],
                'Currency Code'
            )->addColumn(
                'payment_data',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Payment'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true],
                'Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true],
                'Updated At'
            );
            $table->addIndex(
                $setup->getIdxName($tableName, ['transaction_id']),
                ['transaction_id']
            );
            $table->addIndex(
                $setup->getIdxName($tableName, ['total_refund_amount']),
                ['total_refund_amount']
            );

            $table->addIndex(
                $setup->getIdxName($tableName, ['customer_email']),
                ['customer_email']
            );

            $table->addIndex(
                $setup->getIdxName($tableName, ['customer_first_name']),
                ['customer_first_name']
            );

            $table->addIndex(
                $setup->getIdxName($tableName, ['customer_last_name']),
                ['customer_last_name']
            );
            $setup->getConnection()->createTable($table);
        }
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     *
     * @throws \Zend_Db_Exception
     */
    protected function createRefundWithoutReceiptItemTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('sm_refund_without_receipt_item');
        if (!$setup->getConnection()->isTableExists($tableName)) {
            $table = $setup->getConnection()->newTable($tableName);
            $table->addColumn(
                'item_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'  => true
                ],
                'Item ID'
            )->addColumn(
                'transaction_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Transaction ID'
            )->addColumn(
                'shift_adjustment_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => true],
                'Shift Adjustment Id'
            )->addColumn(
                'shift_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Shift Id'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Product ID'
            )->addColumn(
                'product_type',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Product ID'
            )->addColumn(
                'product_options',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Product Options'
            )->addColumn(
                'product_sku',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Product SKU'
            )->addColumn(
                'product_name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Product Name'
            )->addColumn(
                'product_qty',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false, 'unsigned' => true],
                'Product Quantity'
            )->addColumn(
                'product_price',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false],
                'Product Refund Price'
            )->addColumn(
                'base_product_price',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false],
                'Base Product Refund Price'
            )->addColumn(
                'row_total',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false],
                'Row total'
            )->addColumn(
                'base_row_total',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false],
                'Base Row Total'
            )->addColumn(
                'sub_total',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false],
                'Sub Total'
            )->addColumn(
                'base_sub_total',
                Table::TYPE_DECIMAL,
                '12,4',
                ['nullable' => false],
                'Base Sub Total'
            )->addColumn(
                'custom_sales_note',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Custom Sales Note'
            )->addColumn(
                'back_to_stock',
                Table::TYPE_SMALLINT,
                3,
                ['nullable' => true],
                'Back To Stock'
            );
            $setup->getConnection()->createTable($table);
        }
    }
}
