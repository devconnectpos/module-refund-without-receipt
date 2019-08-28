<?php
/**
 * Created by Nomad
 * Date: 7/10/19
 * Time: 4:16 PM
 */

namespace SM\RefundWithoutReceipt\Api;

use SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface;

interface RefundWithoutReceiptTransactionRepositoryInterface
{

    /**
     * @param \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface $transaction
     *
     * @return \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface
     */
    public function save(RefundWithoutReceiptTransactionInterface $transaction);

    /**
     * Loads a specified transaction.
     *
     * @param int $id The Transaction ID.
     * @return \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface
     */
    public function get($id);

    /**
     * @param \SM\RefundWithoutReceipt\Api\Data\RefundWithoutReceiptTransactionInterface $transaction
     *
     * @return void
     */
    public function delete(RefundWithoutReceiptTransactionInterface $transaction);
}
