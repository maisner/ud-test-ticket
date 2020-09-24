<?php declare(strict_types = 1);

namespace Maisner\App;

use Nette\Configurator;


class Bootstrap {
	public static function boot(): Configurator {
		$configurator = new Configurator;

		$configurator->setDebugMode(TRUE);
		$configurator->setTimeZone('Europe/Prague');

		$configurator->enableTracy(__DIR__ . '/../log');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig(__DIR__ . '/config/config.neon');
		$configurator->addConfig(__DIR__ . '/config/config.php');
		$configurator->addConfig(__DIR__ . '/config/local.neon');

		return $configurator;
	}
}
