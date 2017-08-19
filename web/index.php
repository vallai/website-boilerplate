<?php

// Load librairies
require_once __DIR__ . '/../vendor/autoload.php';

// Load constants
require __DIR__ . '/../app/config/constants.php';

// Load config file
$app = require __DIR__ . '/../app/bootstrap.php';

$app->run();