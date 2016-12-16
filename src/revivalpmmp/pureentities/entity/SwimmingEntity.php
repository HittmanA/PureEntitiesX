<?php
namespace revivalpmmp\pureentities\entity;

use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\entity\Creature;

use revivalpmmp\pureentities\entity\animal\Animal;

abstract class SwimmingEntity extends BaseEntity {
    
    /*
     * TODO:
     * Adjust updateMove and set gravity to 0 if entity is in water
     */
    
    protected function checkTarget(){
        if($this->isKnockback()){
            return;
        }

        $target = $this->baseTarget;
        if(!$target instanceof Creature or !$this->targetOption($target, $this->distanceSquared($target))){
            $near = PHP_INT_MAX;
            foreach ($this->getLevel()->getEntities() as $creature){
                if($creature === $this || !($creature instanceof Creature) || $creature instanceof Animal){
                    continue;
                }

                if($creature instanceof BaseEntity && $creature->isFriendly() == $this->isFriendly()){
                    continue;
                }

                $distance = $this->distanceSquared($creature);

                if($distance > $near or !$this->targetOption($creature, $distance)){
                    continue;
                }
                $near = $distance;

                $this->moveTime = 0;
                $this->baseTarget = $creature;
            }
        }

        if($this->baseTarget instanceof Creature && $this->baseTarget->isAlive()){
            return;
        }

        if($this->moveTime <= 0 or !($this->baseTarget instanceof Vector3)){
            $x = mt_rand(20, 100);
            $z = mt_rand(20, 100);
            $this->moveTime = mt_rand(300, 1200);
            $this->baseTarget = $this->add(mt_rand(0, 1) ? $x : -$x, 0, mt_rand(0, 1) ? $z : -$z);
        }
    }

    /**
     * @param int $tickDiff
     *
     * @return null|Vector3
     */
    public function updateMove($tickDiff){
        if(!$this->isMovement()){
            return null;
        }

        if($this->isKnockback()){
            $this->move($this->motionX * $tickDiff, $this->motionY, $this->motionZ * $tickDiff);
            $this->motionY -= 0.2 * $tickDiff;
            $this->updateMovement();
            return null;
        }
        
        $before = $this->baseTarget;
        $this->checkTarget();
        if($this->baseTarget instanceof Creature or $before !== $this->baseTarget){
            $x = $this->baseTarget->x - $this->x;
            $y = $this->baseTarget->y - $this->y;
            $z = $this->baseTarget->z - $this->z;

            $diff = abs($x) + abs($z);
            if($x ** 2 + $z ** 2 < 0.7){
                $this->motionX = 0;
                $this->motionZ = 0;
            }else{
                $this->motionX = $this->getSpeed() * 0.15 * ($x / $diff);
                $this->motionZ = $this->getSpeed() * 0.15 * ($z / $diff);
            }
            $this->yaw = -atan2($x / $diff, $z / $diff) * 180 / M_PI;
            $this->pitch = $y == 0 ? 0 : rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2)));
        }

        $dx = $this->motionX * $tickDiff;
        $dz = $this->motionZ * $tickDiff;
        if($this->stayTime > 0){
            $this->stayTime -= $tickDiff;
            $this->move(0, $this->motionY * $tickDiff, 0);
        }else{
            $be = new Vector2($this->x + $dx, $this->z + $dz);
            $this->move($dx, $this->motionY * $tickDiff, $dz);
            $af = new Vector2($this->x, $this->z);

            if(($be->x != $af->x || $be->y != $af->y)){
                $this->moveTime -= 90 * $tickDiff;
            }
        }

        $this->updateMovement();
        return $this->baseTarget;
    }
}
