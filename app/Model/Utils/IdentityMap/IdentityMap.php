<?php declare(strict_types = 1);

namespace Maisner\App\Model\Utils\IdentityMap;

use Maisner\App\Model\EntityInterface;
use Nette\Utils\ArrayHash;
use Nette\Utils\ArrayList;

class IdentityMap implements IdentityMapInterface {

	/**
	 * @var ArrayHash|EntityInterface[] key is entity_id
	 */
	protected ArrayHash $map;

	public function __construct() {
		$this->map = new ArrayHash;
	}

	public function add(EntityInterface $entity): IdentityMapInterface {
		$this->map->offsetSet($entity->getId(), $entity);

		return $this;
	}

	public function get(int $id): ?EntityInterface {
		if ($this->map->offsetExists($id)) {
			return $this->map->offsetGet($id);
		}

		return NULL;
	}

	public function remove(int $id): IdentityMapInterface {
		if ($this->map->offsetExists($id)) {
			$this->map->offsetUnset($id);
		}

		return $this;
	}

	public function getAll(): ArrayList {
		$list = new ArrayList;

		foreach ($this->map as $entity) {
			$list[] = $entity;
		}

		return $list;
	}

}
