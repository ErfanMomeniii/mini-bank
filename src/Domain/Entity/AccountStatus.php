<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Entity;

enum AccountStatus: string
{
    case Active = 'Active';
    case Inactive = 'Inactive';
}
