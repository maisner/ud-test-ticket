<?php


namespace Maisner\App\Tests\Unit\Model\Utils;


use Maisner\App\Model\EntityInterface;
use Maisner\App\Model\Utils\IdentityMap\IdentityMap;
use Nette\Utils\ArrayList;
use PHPUnit\Framework\TestCase;

class IdentityMapTest extends TestCase {

	protected IdentityMap $identityMap;

	public function setUp(): void {
		$this->identityMap = new IdentityMap();
	}

	public function testAddEntity(): void {
		$this->identityMap->add($this->createEntityMock(1));

		self::assertSame(1, $this->identityMap->getAll()->count());
		self::assertInstanceOf(EntityInterface::class, $this->identityMap->get(1));
		self::assertSame(1, $this->identityMap->get(1)->getId());
	}

	public function testAddMultipleEntities(): void {
		for ($i = 1; $i <= 10000; $i++) {
			$this->identityMap->add($this->createEntityMock($i));
		}

		self::assertSame(10000, $this->identityMap->getAll()->count());
		self::assertSame(6789, $this->identityMap->get(6789)->getId());
	}

	public function testGetAll(): void {
		self::assertInstanceOf(ArrayList::class, $this->identityMap->getAll());
	}

	public function testRemoveIdentity(): void {
		for ($i = 1; $i <= 10; $i++) {
			$this->identityMap->add($this->createEntityMock($i));
		}

		self::assertSame(6, $this->identityMap->get(6)->getId());

		$this->identityMap->remove(6);
		self::assertNull($this->identityMap->get(6));
	}

	protected function createEntityMock(int $id) {
		$entityMock = $this->createMock(EntityInterface::class);
		$entityMock->method('getId')->willReturn($id);

		return $entityMock;
	}

}
