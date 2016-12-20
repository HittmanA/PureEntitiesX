<?php

namespace revivalpmmp\pureentities\task;

use pocketmine\scheduler\PluginTask;
use revivalpmmp\pureentities\PureEntities;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\level\generator\biome\Biome;
use pocketmine\block\Grass;
use pocketmine\math\Vector3;

class AutoSpawnAnimalTask extends PluginTask {

    private $plugin;

    public function __construct(PureEntities $plugin) {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }
    
    public function onRun($currentTick){
        $entities = [];
        $valid = false;
        foreach($this->plugin->getServer()->getLevels() as $level) {
            foreach($level->getPlayers() as $player){
                foreach($level->getEntities() as $entity) {
                    if($player->distance($entity) <= $this->getConfig()->getNested("check.radius")) {
                        $valid = true;
                        $entities[] = $entity;
                    }
                }
        
                if($valid && count($entities) <= $this->getConfig()->getNested("check.maxanimals") && $this->getConfig()->getNested("spawn.animals") === true) {
                    $x = $player->x + mt_rand(-$this->getConfig()->getNested("spawn.maxradius"), $this->getConfig()->getNested("spawn.maxradius"));
                    $z = $player->z + mt_rand(-$this->getConfig()->getNested("spawn.maxradius"), $this->getConfig()->getNested("spawn.maxradius"));
                    $pos = new Position(
                        $x,
                        ($y = $level->getHighestBlockAt($x, $z) + 1),
                        $z,
                        $level
                    );
                } else {
                    return;
                }
                
                $type = 11; // If $type is NOT set, it won't dump errors.
                if($level->getBiomeId($x, $z) === null) {
                    $biome = Biome::PLAINS;
                } else {
                    $biome = $level->getBiomeId($x, $z);
                }
                $probability = mt_rand(1, 100);
                
                $block = $level->getBlock(new Vector3($x, $y - 1, $z));
                $backupblock = $level->getBlock(new Vector3($x, $y - 2, $z));
                
                /*
                 * Plains Biome Animal Generator
                 * Entities:
                 * - Chicken            20%
                 * - Cow                20%
                 * - Horse              5%
                 * - Pig                20%
                 * - Sheep              20%
                 * - Rabbit             15%
                 */
                if($biome === Biome::PLAINS) {
                    if($probability <= 20) {
                        $type = 10; // Chicken
                    } elseif($probability <= 40) {
                        $type = 11; // Cow
                    } elseif($probability <= 45) {
                        $type = 23; // Horse
                    } elseif($probability <= 65) {
                        $type = 12; // Pig
                    } elseif($probability <= 85) {
                        $type = 13; // Sheep
                    } else {
                        $type = 18; // Rabbit
                    }
                }
                
                /*
                 * (Birch) Forest | Swamp | (Small) Mountains Biome Animal Generator
                 * Entities:
                 * - Chicken            20%
                 * - Cow                20%
                 * - Pig                20%
                 * - Sheep              20%
                 * - Rabbit             20%
                 */
                elseif($biome === Biome::FOREST || $biome === Biome::SWAMP || $biome === Biome::BIRCH_FOREST || $biome === Biome::SMALL_MOUNTAINS || $biome === Biome::MOUNTAINS) {
                    if($probability <= 20) {
                        $type = 10; // Chicken
                    } elseif($probability <= 40) {
                        $type = 11; // Cow
                    } elseif($probability <= 60) {
                        $type = 12; // Pig
                    } elseif($probability <= 80) {
                        $type = 13; // Sheep
                    } else {
                        $type = 18; // Rabbit
                    }
                }
                
                /*
                 * Desert | Swamp Biome Animal Generator
                 * Entities:
                 * - Rabbit             100%
                 */
                elseif($biome === Biome::DESERT) {
                    $type = 18; // Rabbit
                }
                
                /*
                 * Taiga | Ice Plains Biome Animal Generator
                 * Entities:
                 * - Wolf               20%
                 * - Cow                30%
                 * - Pig                25%
                 * - Chicken            25%
                 */
                elseif($biome === Biome::TAIGA || $biome === Biome::ICE_PLAINS) {
                    if($probability <= 20) {
                        $type = 14; // Wolf
                    } elseif($probability <= 50) {
                        $type = 11; // Cow
                    } elseif($probability <= 75) {
                        $type = 12; // Pig
                    } else {
                        $type = 10; // Zombie
                    } 
                }
                
                /*
                 * Ocean | River Biome Animal Generator
                 * Entities:
                 * - Squid              100%
                 */
                elseif($biome === Biome::RIVER || $biome === Biome::OCEAN) {
                    //$type = 17; // Squid (Yet to be implemented)
                }
                
                $time = $level->getTime() % Level::TIME_FULL;
                
                if(
                    !($player->distance($pos) <= $this->getConfig()->getNested("spawn.minradius")) &&
                    ($time <= Level::TIME_SUNSET || $time >= Level::TIME_SUNRISE) &&
                    ($block instanceof Grass || $backupblock instanceof Grass)
                ) {
                    $this->plugin->scheduleCreatureSpawn($pos, $type, $level, "Animal");
                }
            }
        }
    }
}