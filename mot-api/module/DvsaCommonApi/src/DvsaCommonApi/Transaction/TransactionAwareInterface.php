<?php

namespace DvsaCommonApi\Transaction;

/**
 * Interface exposing transactional capabilities to components implementing
 * through TransactionExecutorInterface.
 *
 * Interface TransactionAwareInterface
 * @package DvsaCommonApi\Transaction
 */
interface TransactionAwareInterface
{
    public function setTransactionExecutor(TransactionExecutorInterface $transactionExecutor);
    public function getTransactionExecutor();
}
