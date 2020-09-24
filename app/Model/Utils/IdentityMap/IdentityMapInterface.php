<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils\IdentityMap;

use Maisner\App\Model\EntityInterface;
use Nette\Utils\ArrayList;

interface IdentityMapInterface {

	public function add(EntityInterface $entity): self;

	public function get(int $id): ?EntityInterface;

	public function remove(int $id): self;

	/** @return ArrayList|EntityInterface[] */
	public function getAll(): ArrayList;

}
