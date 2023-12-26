<?php

// APPLICATION
define('APP_URL',   'http://localhost:8000');
define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('APP_PATH',  ROOT_PATH . '/app');
define('VIEW_PATH', ROOT_PATH . '/resources/views');

// DATABASE
define('DB_HOST',    'db');
define('DB_NAME',    'forgedb');
define('DB_USER',    'admin');
define('DB_PASS',    'secret');
define('DB_CHARSET', 'utf8');