<?php declare(strict_types = 1);

namespace Maisner\App\Tests\Integration;

use PDO;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

abstract class DbTestCase extends TestCase {

	use TestCaseTrait {
		setUp as protected traitSetUp;
	}

	/** @var PDO */
	private static $pdo;

	/** @var Connection  */
	private $conn;

	protected function setUp(): void {
		parent::setUp();
		$this->traitSetUp();
	}

	final public function getConnection(): Connection {
		if ($this->conn === NULL) {
			if (self::$pdo === NULL) {
				self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
			}
			$this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
		}

		return $this->conn;
	}
}
