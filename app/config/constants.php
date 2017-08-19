<?php

$constants = [
	'BASE_DIRECTORY_PROJECT' => __DIR__ . '/../..',
	'WEB_DIRECTORY' 		 => __DIR__ . '/../../web'
];

foreach ($constants as $key => $value) {
	define($key, $value);
}