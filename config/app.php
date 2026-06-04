<?php

return [
    'env' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => ($_ENV['APP_ENV'] ?? 'local') === 'local',
];
