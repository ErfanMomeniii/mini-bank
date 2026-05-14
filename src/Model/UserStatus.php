<?php
declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Model;

/**
 * Possible statuses for a user.
 *
 * - Active:  The user can log in and perform banking operations.
 * - Blocked: The user's access is restricted (e.g. due to fraud or policy violation).
 */
enum UserStatus: string
{
    case Active = 'Active';
    case Blocked = 'Blocked';
}