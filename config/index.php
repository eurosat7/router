<?php

declare(strict_types=1);

return new \app\dto\Config(
    viewPath: dirname(__DIR__, 1) . '/views/',
    aliases: [
        'account' => 'user',
    ],
);
