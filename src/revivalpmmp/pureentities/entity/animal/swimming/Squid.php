<?php
namespace revivalpmmp\pureentities\entity\animal\swimming;

use pocketmine\item\Item;

use revivalpmmp\pureentities\entity\animal\SwimmingAnimal;

class Squid extends SwimmingAnimal { // TODO possibly extend the pocketmine squid class ??
	const NETWORK_ID = 17;

	public $width = 0.95;
	public $length = 0.95;
	public $height = 0.95;

	public function getName(){
		return "Squid";
	}

	public function initEntity(){
		parent::initEntity();
		$this->setMaxHealth(5);
	}

	public function getDrops(){
		return [
			Item::get(Item::DYE, 0, mt_rand(1, 3))
		];
	}
}
