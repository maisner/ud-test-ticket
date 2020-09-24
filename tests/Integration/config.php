<?php declare(strict_types = 1);

return [
	'database' => [
		'dsn'      => \sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_TEST_HOST'], $_ENV['DB_TEST_NAME']),
		'user'     => $_ENV['DB_TEST_USER'],
		'password' => $_ENV['DB_TEST_PASS']
	]
];
