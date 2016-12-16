<?php
namespace revivalpmmp\pureentities\entity\animal\walking;

use pocketmine\item\ItemBlock;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\entity\Creature;

use revivalpmmp\pureentities\entity\animal\JumpingAnimal;

class Rabbit extends JumpingAnimal{
    const NETWORK_ID = 18;

    public $width = 0.5;
    public $height = 0.5;

    public function getSpeed() : float{
        return 1.2;
    }
    
    public function getName(){
        return "Rabbit";
    }

    public function isBaby() : bool {
	    return parent::isBaby();
    }

	public function initEntity(){
        parent::initEntity();

        $this->setMaxHealth(3);
    }

    public function targetOption(Creature $creature, float $distance) : bool{
        if($creature instanceof Player){
            return $creature->spawned && $creature->isAlive() && !$creature->closed && ($creature->getInventory()->getItemInHand()->getId() == Item::CARROT || $creature->getInventory()->getItemInHand()->getId() == ItemBlock::DANDELION) && $distance <= 8;
        }
        return false;
    }

    public function getDrops(){
    	$rand = mt_rand(0,99);
    	if($rand < 10) {
		    if(!$this->isOnFire()) {
			    return [
				    Item::get(Item::RABBIT_HIDE, 0, mt_rand(0,1)),
				    Item::get(Item::RAW_RABBIT,0,mt_rand(0,1)),
				    Item::get(Item::RABBIT_FOOT,0,1)
			    ];
		    }
		    return [
			    Item::get(Item::RABBIT_HIDE, 0, mt_rand(0,1)),
			    Item::get(Item::COOKED_RABBIT,0,mt_rand(0,1)),
			    Item::get(Item::RABBIT_FOOT,0,1)
		    ];
	    }
	    if(!$this->isOnFire()) {
		    return [
			    Item::get(Item::RABBIT_HIDE, 0, mt_rand(0,1)),
			    Item::get(Item::RAW_RABBIT,0,mt_rand(0,1))
		    ];
	    }
	    return [
		    Item::get(Item::RABBIT_HIDE, 0, mt_rand(0,1)),
		    Item::get(Item::COOKED_RABBIT,0,mt_rand(0,1))
	    ];
    }
}