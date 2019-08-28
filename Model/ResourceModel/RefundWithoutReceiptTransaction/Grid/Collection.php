<?php
/**
 * Created by Nomad
 * Date: 7/10/19
 * Time: 10:37 AM
 */

namespace SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptTransaction\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface;
use Zend_Db_Expr;

class Collection extends SearchResult
{

    /**
     * Fields map for correlation names & real selected fields
     *
     * @var array
     */
    protected $_map
        = [
            'fields' => [
                'store_id' => 'main_table.store_id',
                'outlet_id'  => 'main_table.outlet_id',
                'created_at' => 'main_table.created_at',
                'outlet_name' => 'outlet.name',
                'register_name' => 'register.name',
                'customer_name' => 'CONCAT(main_table.customer_first_name, \' \', main_table.customer_last_name)',
            ]
        ];

    /**
     * Collection constructor.
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface    $entityFactory
     * @param \Psr\Log\LoggerInterface                                     $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface                    $eventManager
     * @param string                                                       $mainTable
     * @param string                                                       $resourceModel
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        $mainTable = 'sm_refund_without_receipt_transaction',
        $resourceModel = 'SM\RefundWithoutReceipt\Model\ResourceModel\RefundWithoutReceiptTransaction'
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel,
            null,
            null
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
             ->columns(
                 [
                     'customer_name' => new Zend_Db_Expr("CONCAT(main_table.customer_first_name, ' ', main_table.customer_last_name)")
                 ]
             )
         ->joinLeft(
             ['outlet' => $this->getTable('sm_xretail_outlet')],
             'main_table.outlet_id = outlet.id',
             ['outlet_name' => 'outlet.name']
         )->joinLeft(
            ['register' => $this->getTable('sm_xretail_register')],
            'main_table.register_id = register.id',
            ['register_name' => 'register.name']
        );

        $this->addFilterToMap(
            'customer_name',
            new Zend_Db_Expr('CONCAT_WS(" ", main_table.customer_first_name, main_table.customer_last_name)')
        );
    }
}
