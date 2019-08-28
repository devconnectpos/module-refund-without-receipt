<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\RefundWithoutReceipt\Block\Adminhtml\Transaction\View;

use DateTime;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\Metadata\ElementFactory;
use Magento\Eav\Model\AttributeDataFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Sales\Model\Order\Address;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Store\Model\ScopeInterface;
use SM\RefundWithoutReceipt\Block\Adminhtml\Transaction\AbstractTransaction;

class Info extends AbstractTransaction
{
    /**
     * Customer service
     *
     * @var \Magento\Customer\Api\CustomerMetadataInterface
     */
    protected $metadata;

    /**
     * Group service
     *
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * Metadata element factory
     *
     * @var \Magento\Customer\Model\Metadata\ElementFactory
     */
    protected $_metadataElementFactory;

    /**
     * @var Address\Renderer
     */
    protected $addressRenderer;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context         $context
     * @param \Magento\Framework\Registry                     $registry
     * @param \Magento\Customer\Api\GroupRepositoryInterface  $groupRepository
     * @param \Magento\Customer\Api\CustomerMetadataInterface $metadata
     * @param \Magento\Customer\Model\Metadata\ElementFactory $elementFactory
     * @param \Magento\Sales\Model\Order\Address\Renderer     $addressRenderer
     * @param array                                           $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        GroupRepositoryInterface $groupRepository,
        CustomerMetadataInterface $metadata,
        ElementFactory $elementFactory,
        Renderer $addressRenderer,
        array $data = []
    ) {
        $this->groupRepository = $groupRepository;
        $this->metadata = $metadata;
        $this->_metadataElementFactory = $elementFactory;
        $this->addressRenderer = $addressRenderer;
        parent::__construct($context, $registry, $data);
    }

    /**
     * Get transaction store name
     *
     * @return null|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTransactionStoreName()
    {
        if ($this->getTransaction()) {
            $storeId = $this->getTransaction()->getStoreId();
            $store = $this->_storeManager->getStore($storeId);
            $name = [$store->getWebsite()->getName(), $store->getGroup()->getName(), $store->getName()];
            return implode('<br/>', $name);
        }

        return null;
    }

    /**
     * Return name of the customer group.
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerGroupName()
    {
        if ($this->getTransaction()) {
            $customerGroupId = $this->getTransaction()->getCustomerGroupId();
            try {
                if ($customerGroupId !== null) {
                    return $this->groupRepository->getById($customerGroupId)->getCode();
                }
            } catch (NoSuchEntityException $e) {
                return '';
            }
        }

        return '';
    }

    /**
     * Get URL to edit the customer.
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerViewUrl()
    {
        if ($this->getTransaction()->getCustomerIsGuest()) {
            return '';
        }

        return $this->getUrl('customer/index/edit', ['id' => $this->getTransaction()->getCustomerId()]);
    }

    /**
     * Get transaction view URL.
     *
     * @param int $transactionId
     * @return string
     */
    public function getViewUrl($transactionId)
    {
        return $this->getUrl('smrefundwr/transaction/view', ['transaction_id' => $transactionId]);
    }

    /**
     * Get order exchange view URL.
     *
     * @param int $orderId
     * @return string
     */
    public function getOrderExchangeViewUrl($orderId)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $orderId]);
    }

    /**
     * Whether Customer IP address should be displayed on sales documents
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function shouldDisplayCustomerIp()
    {
        return !$this->_scopeConfig->isSetFlag(
            'sales/general/hide_customer_ip',
            ScopeInterface::SCOPE_STORE,
            $this->getTransaction()->getStoreId()
        );
    }

    /**
     * Check if is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    /**
     * Get object created at date affected with object store timezone
     *
     * @param mixed $store
     * @param string $createdAt
     * @return \DateTime
     */
    public function getCreatedAtStoreDate($store, $createdAt)
    {
        return $this->_localeDate->scopeDate($store, $createdAt, true);
    }

    /**
     * Get timezone for store
     *
     * @param mixed $store
     * @return string
     */
    public function getTimezoneForStore($store)
    {
        return $this->_localeDate->getConfigTimezone(
            ScopeInterface::SCOPE_STORE,
            $store->getCode()
        );
    }

    /**
     * Get object created at date
     *
     * @param string $createdAt
     *
     * @return \DateTime
     * @throws \Exception
     */
    public function getTransactionAdminDate($createdAt)
    {
        return $this->_localeDate->date(new DateTime($createdAt));
    }

    /**
     * Returns string with formatted address
     *
     * @param Address $address
     * @return null|string
     */
    public function getFormattedAddress(Address $address)
    {
        return $this->addressRenderer->format($address, 'html');
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\LocalizedException
     * @since 100.2.0
     */
    public function getChildHtml($alias = '', $useCache = true)
    {
        $layout = $this->getLayout();

        if ($alias || !$layout) {
            return parent::getChildHtml($alias, $useCache);
        }

        $childNames = $layout->getChildNames($this->getNameInLayout());
        $outputChildNames = array_diff($childNames, ['extra_customer_info']);

        $out = '';
        foreach ($outputChildNames as $childName) {
            $out .= $layout->renderElement($childName, $useCache);
        }

        return $out;
    }
}
