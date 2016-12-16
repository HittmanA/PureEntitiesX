<?php
namespace revivalpmmp\pureentities\entity\animal\walking;

use pocketmine\entity\Creature;

use revivalpmmp\pureentities\entity\animal\FlyingAnimal;

class Bat extends FlyingAnimal{
    //TODO: This isn't implemented yet
    const NETWORK_ID = 13;

    public $width = 0.3;
    public $height = 0.3;

    public function getName(){
        return "Bat";
    }

    public function initEntity(){
        parent::initEntity();

        $this->setMaxHealth(6);
    }

    public function targetOption(Creature $creature, float $distance) : bool{
        return false;
    }

    public function getDrops(){
        return [];
    }

}
