<?php


namespace SM\RefundWithoutReceipt\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $this->addTaxData($setup);
        }
        
        if (version_compare($context->getVersion(), '0.0.3', '<')) {
            $this->addShiftColumns($setup);
        }
        
        $installer->endSetup();
    }

    protected function addTaxData(SchemaSetupInterface $setup)
    {
        $installer = $setup;
        $tableName = $installer->getTable('sm_refund_without_receipt_transaction');

        $installer->getConnection()->addColumn(
            $tableName,
            'tax_percent',
            [
                'type'    => Table::TYPE_DECIMAL,
	            'length'   => '12,4',
	            'nullable' => true,
	            'default'  => 0,
                'comment' => 'Tax Percent',
            ]
        );

        $installer->getConnection()->addColumn(
            $tableName,
            'tax_amount',
            [
                'type'    => Table::TYPE_DECIMAL,
	            'length'   => '12,4',
	            'nullable' => true,
	            'default'  => 0,
                'comment' => 'Tax Amount',
            ]
        );
        
        $installer->getConnection()->addColumn(
            $tableName,
            'base_tax_amount',
            [
                'type'    => Table::TYPE_DECIMAL,
	            'length'   => '12,4',
	            'nullable' => true,
	            'default'  => 0,
                'comment' => 'Base Tax Amount',
            ]
        );
        
        $installer->getConnection()->addColumn(
            $tableName,
            'subtotal_refund_amount',
            [
                'type'    => Table::TYPE_DECIMAL,
	            'length'   => '12,4',
	            'nullable' => true,
                'comment' => 'Subtotal Amount',
            ]
        );
        
        $installer->getConnection()->addColumn(
            $tableName,
            'base_subtotal_refund_amount',
            [
                'type'    => Table::TYPE_DECIMAL,
	            'length'   => '12,4',
	            'nullable' => true,
                'comment' => 'Base Subtotal Amount',
            ]
        );
    }
	
	protected function addShiftColumns(SchemaSetupInterface $setup)
	{
		$installer = $setup;
		$tableName = $installer->getTable('sm_refund_without_receipt_transaction');
		
		$installer->getConnection()->addColumn(
			$tableName,
			'shift_adjustment_id',
			[
				'type'    => Table::TYPE_INTEGER,
				'nullable' => true,
				'comment' => 'Shift Adjustment Id',
			]
		);
		
		$installer->getConnection()->addColumn(
			$tableName,
			'shift_id',
			[
				'type'    => Table::TYPE_INTEGER,
				'nullable' => true,
				'comment' => 'Shift Id',
			]
		);
		
		//remove shift columns from item table
		$itemTableName = $installer->getTable('sm_refund_without_receipt_item');
		$installer->getConnection()->dropColumn($itemTableName, 'shift_adjustment_id');
		$installer->getConnection()->dropColumn($itemTableName, 'shift_id');
	}
}
