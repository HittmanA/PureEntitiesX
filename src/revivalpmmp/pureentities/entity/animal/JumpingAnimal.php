<?php
namespace revivalpmmp\pureentities\entity\animal;

use revivalpmmp\pureentities\entity\JumpingEntity;

abstract class JumpingAnimal extends JumpingEntity implements Animal{

	public function getSpeed() : float{
		return 1.0;
	}

	public function initEntity(){
		parent::initEntity();

		if($this->getDataFlag(self::DATA_FLAG_BABY , 0) === null){
			$this->setDataFlag(self::DATA_FLAG_BABY, self::DATA_TYPE_BYTE, 0);
		}
	}

	public function isBaby() : bool{
		return $this->getDataFlag(parent::DATA_FLAG_BABY,0);
	}

	public function entityBaseTick($tickDiff = 1){
		// TODO
	}

	public function onUpdate($currentTick){
		// TODO
	}
}