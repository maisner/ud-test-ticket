<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils\IdentityMap\Factory;


use Maisner\App\Model\Utils\IdentityMap\IdentityMap;
use Maisner\App\Model\Utils\IdentityMap\IdentityMapInterface;

class IdentityMapFactory implements IdentityMapFactoryInterface {

	public function create(): IdentityMapInterface {
		return new IdentityMap();
	}
}
