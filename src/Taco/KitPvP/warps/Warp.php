<?php namespace Taco\KitPvP\warps;

use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;
use Taco\KitPvP\Main;

class Warp {

    /** @var string */
    private string $name;

    /** @var string */
    private string $permission;

    /** @var Vector3 */
    private Vector3 $pos;

    /** @var World */
    private World $world;

    public function __construct(string $name, ?string $permission, Vector3 $pos, World $world) {
        $this->name = $name;
        $this->permission = $permission;
        $this->pos = $pos;
        $this->world = $world;
    }

    /*** @return string */
    public function getName() : string {
        return $this->name;
    }

    /**
     * Returns whether the player has permission to warp
     *
     * @param Player $player
     * @return bool
     */
    public function canWarp(Player $player) : bool {
        if ($this->permission == null) return true;
        return $player->hasPermission($this->permission);
    }

    /**
     * Warps the player
     *
     * @param Player $player
     * @return void
     */
    public function warp(Player $player) : void {
        if (!$this->world->isLoaded()) {
            $player->sendMessage(Main::$config["warps"]["warp-fail"]);
            return;
        }
        $player->teleport($this->world->getSpawnLocation());
        $player->teleport($this->pos);
        $player->sendMessage(Main::$config["warps"]["warp-success"]);
    }

}