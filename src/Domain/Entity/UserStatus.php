<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Entity;

enum UserStatus: string
{
    case Active = 'Active';
    case Blocked = 'Blocked';
}
