<?php declare(strict_types = 1);

return [
	'database' => [
		'dsn'      => \sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_HOST'], $_ENV['DB_NAME']),
		'user'     => $_ENV['DB_USER'],
		'password' => $_ENV['DB_PASS']
	]
];
