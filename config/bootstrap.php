<?php

use Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . "/logs/error.log");

$env = getenv('APPLICATION_ENV') ?: 'prod';

(new Dotenv(__DIR__ . '/environment', '.env.' . $env))->load();