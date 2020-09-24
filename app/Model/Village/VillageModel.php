<?php declare(strict_types = 1);

namespace Maisner\App\Model\Village;


use Maisner\App\Model\Utils\IdentityMap\Factory\IdentityMapFactoryInterface;
use Maisner\App\Model\Utils\IdentityMap\IdentityMapInterface;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\ArrayList;

class VillageModel {

	use SmartObject;

	/** @var string */
	private const TABLE = 'village';

	private Context $db;

	private IdentityMapInterface $identityMap;

	public function __construct(Context $db, IdentityMapFactoryInterface $identityMapFactory) {
		$this->db = $db;
		$this->identityMap = $identityMapFactory->create();
	}

	/**
	 * @return ArrayList|Village[]
	 */
	public function findAll(): ArrayList {
		$list = new ArrayList();

		/** @var ActiveRow $row */
		foreach ($this->db->table(self::TABLE)->fetchAll() as $row) {
			$list[] = $this->villageFactory($row);
		}

		return $list;
	}

	private function villageFactory(ActiveRow $row): Village {
		$id = (int)$row->offsetGet('id');
		$name = (string)$row->offsetGet('name');

		/** @var Village|null $entity */
		$entity = $this->identityMap->get($id);

		if ($entity !== NULL) {
			return $entity;
		}

		$entity = new Village($id, $name);
		$this->identityMap->add($entity);

		return $entity;
	}
}
