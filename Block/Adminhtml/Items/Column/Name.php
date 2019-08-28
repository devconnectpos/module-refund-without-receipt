<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\RefundWithoutReceipt\Block\Adminhtml\Items\Column;

use Magento\Framework\Filter\TruncateFilter\Result;

class Name extends DefaultColumn
{

    /**
     * @var Result
     */
    private $truncateResult = null;

    /**
     * Truncate string
     *
     * @param string  $value
     * @param int     $length
     * @param string  $etc
     * @param string &$remainder
     * @param bool    $breakWords
     *
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function truncateString($value, $length = 80, $etc = '...', &$remainder = '', $breakWords = true)
    {
        $this->truncateResult = $this->filterManager->truncateFilter(
            $value,
            ['length' => $length, 'etc' => $etc, 'breakWords' => $breakWords]
        );

        return $this->truncateResult->getValue();
    }

    /**
     * Add line breaks and truncate value
     *
     * @param string $value
     *
     * @return array
     */
    public function getFormattedOption($value)
    {
        $remainder = '';
        $this->truncateString($value, 55, '', $remainder);
        $result = [
            'value'     => nl2br($this->truncateResult->getValue()),
            'remainder' => nl2br($this->truncateResult->getRemainder())
        ];

        return $result;
    }
}
