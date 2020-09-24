<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils\IdentityMap\Factory;

use Maisner\App\Model\Utils\IdentityMap\IdentityMapInterface;

interface IdentityMapFactoryInterface {
	
	public function create(): IdentityMapInterface;

}
