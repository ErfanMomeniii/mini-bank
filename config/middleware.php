<?php

declare(strict_types=1);

use ErfanMomeniii\MiniBank\Infrastructure\Http\JsonErrorHandler;
use Slim\App;

return function (App $app): void {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    $settings = $app->getContainer()->get('settings');
    $displayErrorDetails = $settings['debug'] ?? false;

    $app->addErrorMiddleware($displayErrorDetails, false, false)
        ->setDefaultErrorHandler(new JsonErrorHandler());
};
