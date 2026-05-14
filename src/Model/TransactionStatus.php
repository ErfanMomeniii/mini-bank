<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Model;

/**
 * Possible statuses for a transaction.
 *
 * - Pending: The transaction has been created but not yet processed.
 * - Success: The transaction was processed and funds transferred successfully.
 * - Failed:  The transaction could not be completed (e.g. insufficient funds, blocked account).
 */
enum TransactionStatus: string
{
    case Pending = 'Pending';
    case Success = 'Success';
    case Failed = 'Failed';
}