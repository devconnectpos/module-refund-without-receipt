<?php
/**
 * Created by Nomad
 * Date: 7/10/19
 * Time: 11:34 AM
 */

namespace SM\RefundWithoutReceipt\Ui\Component\Listing\Column\Transaction;


use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class GridAction extends Column
{

    const ROW_URL = 'smrefundwr/transaction/view';
    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    private $rowUrl;

    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     * @param string             $rowUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $rowUrl = self::ROW_URL
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->rowUrl     = $rowUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['transaction_id'])) {
                    $item[$this->getData('name')]['view'] = [
                        'href'  => $this->urlBuilder->getUrl(
                            $this->rowUrl,
                            [
                                'transaction_id' => $item['transaction_id']
                            ]
                        ),
                        'label' => __('View')
                    ];
                }
                if (isset($item['exchange_order_id'])) {
                    $item[$this->getData('name')]['view_exchange'] = [
                        'href'  => $this->urlBuilder->getUrl(
                            'sales/order/view',
                            [
                                'order_id' => $item['exchange_order_id']
                            ]
                        ),
                        'label' => __('View Exchange Order')
                    ];
                }
            }
        }

        return $dataSource;
    }
}
