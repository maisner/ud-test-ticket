<?php declare(strict_types = 1);

namespace Maisner\App\Model\UserAdmin;


use Maisner\App\Model\Exception\EntityNotFoundException;
use Maisner\App\Model\Utils\IdentityMap\Factory\IdentityMapFactoryInterface;
use Maisner\App\Model\Utils\IdentityMap\IdentityMapInterface;
use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\Utils\ArrayList;

class UserAdminModel {

	use SmartObject;

	/** @var string */
	private const TABLE = 'user_admin';

	private Context $db;

	private IdentityMapInterface $identityMap;

	public function __construct(Context $db, IdentityMapFactoryInterface $identityMapFactory) {
		$this->db = $db;
		$this->identityMap = $identityMapFactory->create();
	}

	/**
	 * @param string $name
	 * @return UserAdmin
	 * @throws EntityNotFoundException
	 */
	public function getByName(string $name): UserAdmin {
		$row = $this->db->table(self::TABLE)->where('name', $name)->fetch();

		if ($row === NULL) {
			throw new EntityNotFoundException(\sprintf('UserAdmin entity with name "%s" not found', $name));
		}

		return $this->userAdminFactory($row);
	}

	/**
	 * @return ArrayList<int, UserAdmin>
	 */
	public function findAll(): ArrayList {
		$list = new ArrayList();

		/** @var ActiveRow $row */
		foreach ($this->db->table(self::TABLE)->fetchAll() as $row) {
			$list[] = $this->userAdminFactory($row);
		}

		return $list;
	}

	private function userAdminFactory(ActiveRow $row): UserAdmin {
		$id = (int)$row->offsetGet('id');
		$name = (string)$row->offsetGet('name');

		/** @var UserAdmin|null $entity */
		$entity = $this->identityMap->get($id);

		if ($entity !== NULL) {
			return $entity;
		}

		$entity = new UserAdmin($id, $name);
		$this->identityMap->add($entity);

		return $entity;
	}

}
