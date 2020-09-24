<?php declare(strict_types = 1);

namespace Maisner\App\Model\Village;


use Maisner\App\Model\EntityInterface;
use Nette\SmartObject;

class Village implements EntityInterface {

	use SmartObject;

	private int $id;

	private string $name;

	public function __construct(int $id, string $name) {
		$this->id = $id;
		$this->name = $name;
	}

	public function getId(): int {
		return $this->id;
	}

	public function getName(): string {
		return $this->name;
	}
}
