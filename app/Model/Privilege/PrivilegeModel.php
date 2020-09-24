<?php declare(strict_types = 1);

namespace Maisner\App\Model\Privilege;


use Maisner\App\Model\Exception\EntityNotFoundException;
use Maisner\App\Model\Utils\IdentityMap\Factory\IdentityMapFactoryInterface;
use Maisner\App\Model\Utils\IdentityMap\IdentityMapInterface;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\ArrayList;

class PrivilegeModel {

	use SmartObject;

	/** @var string */
	private const TABLE = 'privilege';

	private Context $db;

	private IdentityMapInterface $identityMap;

	public function __construct(Context $db, IdentityMapFactoryInterface $identityMapFactory) {
		$this->db = $db;
		$this->identityMap = $identityMapFactory->create();
	}

	public function getById(int $id): Privilege {
		/** @var Privilege|null $entity */
		$entity = $this->identityMap->get($id);

		if ($entity !== NULL) {
			return $entity;
		}

		$row = $this->db->table(self::TABLE)->get($id);

		if ($row === NULL) {
			throw new EntityNotFoundException();
		}

		return $this->privilegeFactory($row);
	}

	/**
	 * @return ArrayList<int, Privilege>
	 */
	public function findAll(): ArrayList {
		$list = new ArrayList();

		/** @var ActiveRow $row */
		foreach ($this->db->table(self::TABLE)->fetchAll() as $row) {
			$list[] = $this->privilegeFactory($row);
		}

		return $list;
	}

	private function privilegeFactory(ActiveRow $row): Privilege {
		$id = (int)$row->offsetGet('id');
		$name = (string)$row->offsetGet('name');

		/** @var Privilege|null $entity */
		$entity = $this->identityMap->get($id);

		if ($entity !== NULL) {
			return $entity;
		}

		$entity = new Privilege($id, $name);
		$this->identityMap->add($entity);

		return $entity;
	}

}
