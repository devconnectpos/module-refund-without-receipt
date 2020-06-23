<?php


namespace SM\RefundWithoutReceipt\Observer;


class DeductRewardPoints implements \Magento\Framework\Event\ObserverInterface
{
	/**
	 * @var \SM\Integrate\Helper\Data
	 */
	private $integrateHelper;
	/**
	 * @var \Magento\Reward\Model\Reward
	 */
	private $rewardInstance = null;
	/**
	 * @var \Magento\Reward\Model\RewardFactory
	 */
	private $rewardFactory;
	/**
	 * @var \Magento\Customer\Api\CustomerRepositoryInterface
	 */
	private $customerRepository;
	/**
	 * @var \Magento\Framework\ObjectManagerInterface
	 */
	private $objectManager;
	
	public function __construct(
		\SM\Integrate\Helper\Data $integrateHelper,
		\Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
		\Magento\Framework\ObjectManagerInterface $objectManager
	) {
		
		$this->integrateHelper = $integrateHelper;
		$this->customerRepository = $customerRepository;
		$this->objectManager = $objectManager;
	}
	
	/**
	 * @param \Magento\Framework\Event\Observer $observer
	 * @throws \Magento\Framework\Exception\LocalizedException
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 * @throws \Exception
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		if (!$this->integrateHelper->isDeductRewardPointsWhenRefundWithoutReceipt()) {
			return;
		}
		
		/** @var \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction $transaction */
		$transaction = $observer->getEvent()->getData('object');
		
		if (!$transaction->getId()) {
			return;
		}
		
		if ($transaction->getCustomerEmail() == \SM\Customer\Helper\Data::DEFAULT_CUSTOMER_RETAIL_EMAIL) {
			return;
		}
		
		/** @var \Magento\Reward\Model\Reward $customerRewardDetails */
		$customerRewardDetails = $this->getRewardModel($transaction);
		
		if (null == $customerRewardDetails || 0 == $customerRewardDetails->getPointsBalance()) {
			return;
		}
		
		$pointsDelta = $this->calculateRewardPointToDeduct($transaction, $customerRewardDetails);
		
		$this->getRewardFactory()->create()->setCustomerId(
			$transaction->getCustomerId()
		)->setWebsiteId(
			$transaction->getStore()->getWebsiteId()
		)->setAction(
			\SM\Integrate\RewardPoint\Magento2EE\Reward::REWARD_ACTION_REFUND_WITHOUT_RECEIPT
		)->setPointsDelta(
			$pointsDelta
		)->setActionEntity(
			$transaction
		)->updateRewardPoints();
	}
	
	/**
	 * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction $transaction
	 * @return \Magento\Reward\Model\Reward|null
	 * @throws \Magento\Framework\Exception\LocalizedException
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 */
	protected function getRewardModel($transaction)
	{
		if (null == $this->rewardInstance) {
			$websiteId = $transaction->getStore()->getWebsiteId();
			$customer = $this->customerRepository->getById($transaction->getCustomerId());
			if ($customer && $customer->getId()) {
				$this->rewardInstance = $this->getRewardFactory()->create()
					->setCustomer($customer)
					->setWebsiteId($websiteId)
					->loadByCustomer();
			}
		}
		return $this->rewardInstance;
	}
	
	/**
	 * @return \Magento\Reward\Model\RewardFactory|mixed
	 */
	protected function getRewardFactory()
	{
		if (null == $this->rewardFactory) {
			$this->rewardFactory = $this->objectManager->get('Magento\Reward\Model\RewardFactory');
		}
		return $this->rewardFactory;
	}
	
	/**
	 * @param \SM\RefundWithoutReceipt\Model\RefundWithoutReceiptTransaction $transaction
	 * @param \Magento\Reward\Model\Reward $customerRewardDetails
	 * @return int
	 */
	protected function calculateRewardPointToDeduct($transaction, $customerRewardDetails)
	{
		$ratePoint    = $customerRewardDetails->getRateToCurrency()->getPoints(true);
		$rateCurrency = $customerRewardDetails->getRateToCurrency()->getCurrencyAmount();
		$amount       = $transaction->getTotalRefundAmount();

		return - $amount * $ratePoint / $rateCurrency;
	}
}
