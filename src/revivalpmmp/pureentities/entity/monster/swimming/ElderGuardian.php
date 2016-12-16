<?php
namespace revivalpmmp\pureentities\entity\monster\swimming;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;

use revivalpmmp\pureentities\entity\monster\Monster;
use revivalpmmp\pureentities\entity\monster\SwimmingMonster;

class ElderGuardian extends SwimmingMonster implements Monster {
	CONST NETWORK_ID = 50;

	public $width = 0.4;
	public $height = 0.2;

	public function getSpeed() : float{
		return 1.4;
	}

	public function initEntity(){
		parent::initEntity();

		$this->setMaxDamage(5,1);
		$this->setMaxDamage(5,2);
		$this->setMaxDamage(12,3);
		$this->setDamage([0, 1, 1, 1]);
	}

	public function getName(){
		return "Guardian";
	}

	public function attackEntity(Entity $player){
		if($this->attackDelay > 10 && $this->distanceSquared($player) < 1){
			$this->attackDelay = 0;

			$ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getDamage());
			$player->attack($ev->getFinalDamage(), $ev);
		}
	}

	public function getDrops(){
		if($this->lastDamageCause instanceof EntityDamageByEntityEvent){
			$meta = 0; //TODO set to wet sponge
			return [
				Item::get(ItemBlock::SPONGE,$meta,mt_rand(0,1)),
				Item::get(409, 0, mt_rand(0,2)),
				Item::get(422,0,mt_rand(0,1)),
				Item::get(Item::RAW_FISH,0,mt_rand(0,1))
			];
		}
		return [];
	}
}
