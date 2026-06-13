<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Entity;

enum TransactionStatus: string
{
    case Pending = 'Pending';
    case Success = 'Success';
    case Failed = 'Failed';
}
