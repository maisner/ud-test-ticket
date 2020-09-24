<?php declare(strict_types = 1);

namespace Maisner\App\Model\Rule;


use Maisner\App\Model\Exception\EntityNotFoundException;
use Maisner\App\Model\Privilege\Privilege;
use Maisner\App\Model\UserAdmin\UserAdmin;
use Maisner\App\Model\UserAdmin\UserAdminModel;
use Maisner\App\Model\Village\Village;
use Maisner\App\Model\Village\VillageModel;
use Nette\Database\Context;
use Nette\Database\DriverException;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Tracy\ILogger;

class RuleModel {

	use SmartObject;

	protected const TABLE = 'rules';

	private Context $db;

	private VillageModel $villageModel;

	private UserAdminModel $userAdminModel;

	private ILogger $logger;

	public function __construct(
		Context $db,
		VillageModel $villageModel,
		UserAdminModel $userAdminModel,
		ILogger $logger
	) {
		$this->db = $db;
		$this->villageModel = $villageModel;
		$this->userAdminModel = $userAdminModel;
		$this->logger = $logger;
	}

	/**
	 * @param string    $userName
	 * @param Privilege $privilege
	 * @return array|Village[]
	 */
	public function get(string $userName, Privilege $privilege): array {
		try {
			$user = $this->userAdminModel->getByName($userName);
		} catch (EntityNotFoundException $e) {
			return [];
		}

		$villages = [];
		foreach ($this->villageModel->findAll() as $village) {
			$villages[$village->getId()] = $village;
		}

		$rules = $this->db->table(self::TABLE)->where('user_admin_id', $user->getId())->fetchAll();

		if (\count($rules) === 0) {
			return $villages;
		}

		$deniedVillageIds = [];
		$allowVillageIds = [];

		/** @var ActiveRow $rule */
		foreach ($rules as $rule) {
			if ((int)$rule->offsetGet('privilege_id') !== $privilege->getId()) {
				continue;
			}

			if ($rule->offsetGet('type') === 'deny') {
				$deniedVillageIds[] = (int)$rule->offsetGet('village_id');
			}

			if ($rule->offsetGet('type') === 'allow') {
				$allowVillageIds[] = (int)$rule->offsetGet('village_id');
			}
		}

		$allowedVillages = [];

		foreach ($villages as $village) {
			if (\in_array($village->getId(), $allowVillageIds, TRUE)) {
				$allowedVillages[] = $village;    //alllowed
				continue;
			}

			$isDenied = \in_array($village->getId(), $deniedVillageIds, TRUE);
			if ($isDenied) {
				continue;    //denied
			}

			$isDeniedInAnythingVillage = \count($deniedVillageIds) > 0;
			if ($isDeniedInAnythingVillage) {
				continue;    //denied
			} else {
				$allowedVillages[] = $village;
			}
		}

		return $allowedVillages;
	}

	/**
	 * @param UserAdmin     $user
	 * @param array|mixed[] $checkBoxValues values example: [privilage_id => [village_id => bool]]
	 */
	public function set(UserAdmin $user, array $checkBoxValues): void {
		$data = [];
		$isAllDenied = TRUE;

		foreach ($checkBoxValues as $privilegeId => $values) {
			foreach ($values as $villageId => $isAllow) {
				if ($isAllow) {
					$isAllDenied = FALSE;
				}

				$data[] = [
					'privilege_id'  => $privilegeId,
					'village_id'    => $villageId,
					'user_admin_id' => $user->getId(),
					'type'          => $isAllow ? 'allow' : 'deny'
				];
			}
		}

		$this->db->beginTransaction();
		try {
			$this->db->table(self::TABLE)->where('user_admin_id', $user->getId())->delete();

			if (!$isAllDenied) {
				$this->db->table(self::TABLE)->insert($data);
			}

			$this->db->commit();
		} catch (DriverException $e) {
			$this->logger->log($e, $this->logger::ERROR);
			$this->db->rollBack();
		}
	}

}
