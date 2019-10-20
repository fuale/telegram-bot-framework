<?php

declare(strict_types=1);

return [
    'telegram_client' => [
        'https://api.telegram.org',
        'proxy_if_you_need',
    ],

    'bot' => ['you_telegram_bot_api_key', 'telegram_bot_username'],

    // Database setting
    'mysql' => [
        'host'     => 'mysql',
        'port'     => 3306,
        'user'     => 'test',
        'password' => 'test',
        'database' => 'test',
    ],
];
