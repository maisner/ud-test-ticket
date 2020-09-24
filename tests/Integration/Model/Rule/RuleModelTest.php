<?php


namespace Maisner\App\Tests\Integration\Model\Rule;


use Maisner\App\Model\Privilege\PrivilegeModel;
use Maisner\App\Model\Rule\RuleModel;
use Maisner\App\Model\UserAdmin\UserAdminModel;
use Maisner\App\Model\Village\Village;
use Maisner\App\Tests\Integration\Bootstrap;
use Maisner\App\Tests\Integration\DbTestCase;
use Nette\Database\Context;
use PHPUnit\DbUnit\DataSet\IDataSet;

class RuleModelTest extends DbTestCase {

	/** @var RuleModel */
	protected $ruleModel;

	/** @var PrivilegeModel */
	protected $privilegeModel;

	/** @var UserAdminModel */
	protected $userAdminModel;

	/** @var Context */
	protected $db;

	protected function setUp(): void {
		parent::setUp();

		$container = Bootstrap::getContainer();

		$this->ruleModel = $container->getByType(RuleModel::class);
		$this->privilegeModel = $container->getByType(PrivilegeModel::class);
		$this->userAdminModel = $container->getByType(UserAdminModel::class);
		$this->db = $container->getByType(Context::class);
	}

	public function testGetForAdam(): void {
		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Adam', $addressBookPrivilege);
		self::assertSame('Praha', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Adam', $searchPrivilege);
		self::assertSame('Praha', $this->parseVillageNames($res));
	}

	public function testGetForBob(): void {
		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Bob', $addressBookPrivilege);
		self::assertSame('Brno', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Bob', $searchPrivilege);
		self::assertSame('Praha', $this->parseVillageNames($res));
	}

	public function testGetForCyril(): void {
		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Cyril', $addressBookPrivilege);
		self::assertSame('Praha, Brno', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Cyril', $searchPrivilege);
		self::assertSame('Brno', $this->parseVillageNames($res));
	}

	public function testGetForDerek(): void {
		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		self::assertSame([], $this->ruleModel->get('Derek', $addressBookPrivilege));
		self::assertSame([], $this->ruleModel->get('Derek', $searchPrivilege));
	}

	public function testGetForNewAddedUser(): void {
		$this->insertFredUser();

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Fred', $addressBookPrivilege);
		self::assertSame('Praha, Brno', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Fred', $searchPrivilege);
		self::assertSame('Praha, Brno', $this->parseVillageNames($res));
	}

	public function testGetForAdamAfterInsertNewVillage(): void {
		$this->insertOstravaVillage();

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Adam', $addressBookPrivilege);
		self::assertSame('Praha', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Adam', $searchPrivilege);
		self::assertSame('Praha', $this->parseVillageNames($res));
	}

	public function testGetForBobAfterInsertNewVillage(): void {
		$this->insertOstravaVillage();

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Bob', $addressBookPrivilege);
		self::assertSame('Brno', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Bob', $searchPrivilege);
		self::assertSame('Praha', $this->parseVillageNames($res));
	}

	public function testGetForCyrilAfterInsertNewVillage(): void {
		$this->insertOstravaVillage();

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Cyril', $addressBookPrivilege);
		self::assertSame('Praha, Brno, Ostrava', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Cyril', $searchPrivilege);
		self::assertSame('Brno', $this->parseVillageNames($res));
	}

	public function testGetForDerekAfterInsertNewVillage(): void {
		$this->insertOstravaVillage();

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		self::assertSame([], $this->ruleModel->get('Derek', $addressBookPrivilege));
		self::assertSame([], $this->ruleModel->get('Derek', $searchPrivilege));
	}

	public function testGetForFredAfterInsertNewVillage(): void {
		$this->insertFredUser();
		$this->insertOstravaVillage();

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Fred', $addressBookPrivilege);
		self::assertSame('Praha, Brno, Ostrava', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Fred', $searchPrivilege);
		self::assertSame('Praha, Brno, Ostrava', $this->parseVillageNames($res));
	}

	public function testSetForAdam(): void {
		$user = $this->userAdminModel->getByName('Adam');

		$values = [
			1 => [
				1 => FALSE,
				2 => TRUE
			],
			2 => [
				1 => FALSE,
				2 => FALSE
			]
		];
		$this->ruleModel->set($user, $values);

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Adam', $addressBookPrivilege);
		self::assertSame('Brno', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Adam', $searchPrivilege);
		self::assertSame('', $this->parseVillageNames($res));
	}

	public function testSetForBob(): void {
		$user = $this->userAdminModel->getByName('Bob');

		$values = [
			1 => [
				1 => FALSE,
				2 => FALSE
			],
			2 => [
				1 => FALSE,
				2 => FALSE
			]
		];
		$this->ruleModel->set($user, $values);

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Bob', $addressBookPrivilege);
		self::assertSame('Praha, Brno', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Bob', $searchPrivilege);
		self::assertSame('Praha, Brno', $this->parseVillageNames($res));
	}

	public function testSetForCyril(): void {
		$user = $this->userAdminModel->getByName('Cyril');

		$values = [
			1 => [
				1 => TRUE,
				2 => TRUE
			],
			2 => [
				1 => TRUE,
				2 => TRUE
			]
		];
		$this->ruleModel->set($user, $values);

		$addressBookPrivilege = $this->privilegeModel->getById(1);
		$searchPrivilege = $this->privilegeModel->getById(2);

		$res = $this->ruleModel->get('Cyril', $addressBookPrivilege);
		self::assertSame('Praha, Brno', $this->parseVillageNames($res));

		$res = $this->ruleModel->get('Cyril', $searchPrivilege);
		self::assertSame('Praha, Brno', $this->parseVillageNames($res));
	}

	protected function insertOstravaVillage(): void {
		$this->db->table('village')->insert(['name' => 'Ostrava']);
	}

	protected function insertFredUser(): void {
		$this->db->table('user_admin')->insert(['name' => 'Fred']);
	}

	/**
	 * @param array|Village[] $result
	 * @return string
	 */
	protected function parseVillageNames(array $result): string {
		$items = [];
		foreach ($result as $village) {
			$items[] = $village->getName();
		}

		return implode(', ', $items);
	}

	/**
	 * Returns the test dataset.
	 *
	 * @return IDataSet<mixed>
	 */
	protected function getDataSet(): IDataSet {
		return $this->createFlatXMLDataSet(__DIR__ . '/ruleModelDataSet.xml');
	}
}
