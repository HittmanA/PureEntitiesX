<?php
namespace revivalpmmp\pureentities\entity\animal\walking;

use pocketmine\entity\Colorable;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\entity\Creature;

use revivalpmmp\pureentities\entity\animal\WalkingAnimal;

class Sheep extends WalkingAnimal implements Colorable{
    const NETWORK_ID = 13;

    public $width = 1.45;
    public $height = 1.12;

    public function getName(){
        return "Sheep";
    }

    public function initEntity(){
        parent::initEntity();

        $this->setMaxHealth(8);
    }

    public function targetOption(Creature $creature, float $distance) : bool{
        if($creature instanceof Player){
            return $creature->spawned && $creature->isAlive() && !$creature->closed && $creature->getInventory()->getItemInHand()->getId() == Item::SEEDS && $distance <= 49;
        }
        return false;
    }

    public function getDrops(){
	    return [
		    Item::get(Item::WOOL, 1, 1)
	    ];
    }
}