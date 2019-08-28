<?php
/**
 * Created by Nomad
 * Date: 7/12/19
 * Time: 10:38 AM
 */

namespace SM\RefundWithoutReceipt\Block\Adminhtml\Transaction;


use IntlDateFormatter;
use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

class View extends Container
{

    /**
     * Block group
     *
     * @var string
     */
    protected $_blockGroup = 'SM_RefundWithoutReceipt';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $registry
     * @param array                                 $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Constructor
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _construct()
    {
        $this->_objectId   = 'transaction_id';
        $this->_controller = 'adminhtml_transaction';
        $this->_mode       = 'view';

        parent::_construct();

        $this->removeButton('delete');
        $this->removeButton('reset');
        $this->removeButton('save');
        $this->setId('smrefundwr_transaction_view');
        $transaction = $this->getTransaction();

        if (!$transaction) {
            return;
        }
    }

    /**
     * @return \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction
     */
    public function getTransaction()
    {
        return $this->coreRegistry->registry('current_refundwr_transaction');
    }

    /**
     * @return int
     */
    public function getTransactionId()
    {
        return $this->getTransaction() ? $this->getTransaction()->getId() : null;
    }

    /**
     * Get header text
     *
     * @return \Magento\Framework\Phrase
     * @throws \Exception
     */
    public function getHeaderText()
    {
        return __(
            'Refund Transaction # %1 | %3',
            $this->getTransaction()->getId(),
            $this->formatDate(
                $this->_localeDate->date(new \DateTime($this->getTransaction()->getCreatedAt())),
                IntlDateFormatter::MEDIUM,
                true
            )
        );
    }

    /**
     * URL getter
     *
     * @param string $params
     * @param array  $params2
     *
     * @return string
     */
    public function getUrl($params = '', $params2 = [])
    {
        $params2['transaction_id'] = $this->getTransactionId();

        return parent::getUrl($params, $params2);
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     *
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Return back url for view grid
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('smrefundwr/transaction/index');
    }

}
