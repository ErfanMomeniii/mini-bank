<?php

declare(strict_types=1);

return [
    'debug' => (bool) ($_ENV['APP_DEBUG'] ?? false),
    'jwt_key' => $_ENV['JWT_KEY'] ?? '',
];
