<?php
namespace revivalpmmp\pureentities\entity;

abstract class JumpingEntity extends BaseEntity{

    /*
     * For slimes, Magma Cubes, and Vampire Bunnies ONLY
     * Not to be confused for normal entity jumping
     */
    
    protected function checkTarget(){
        //TODO
    }

    public function updateMove($tickDiff){
        // TODO
        return null;
    }
}