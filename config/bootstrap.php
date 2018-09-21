<?php

use Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$env = getenv('APPLICATION_ENV') ?: 'prod';

(new Dotenv(__DIR__ . '/environment', '.env.' . $env))->load();