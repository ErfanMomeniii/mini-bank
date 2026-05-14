<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Model;

/**
 * Possible statuses for a bank account.
 *
 * - Active:   The account can send and receive transactions.
 * - Inactive: The account is disabled and cannot participate in transactions.
 */
enum AccountStatus: string
{
    case Active = 'Active';
    case Inactive = 'Inactive';
}