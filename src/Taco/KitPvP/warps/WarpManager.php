<?php namespace Taco\KitPvP\warps;

use pocketmine\player\Player;
use pocketmine\Server;
use Taco\KitPvP\Main;
use Taco\KitPvP\utils\VectorUtils;
use Taco\KitPvP\warps\commands\WarpCommand;

class WarpManager {

    /** @var array<Warp> */
    private array $warps = [];

    public function __construct() {
        foreach(Main::$config["warps"]["types"] as $name => $data) {
            $this->warps[] = new Warp(
                $name,
                $data["permission"] ?? null,
                VectorUtils::stringToVector($data["position"]),
                Server::getInstance()->getWorldManager()->getWorldByName($data["world"])
            );
        }

        Server::getInstance()->getCommandMap()->registerAll("KitPvP", [
            new WarpCommand()
        ]);
    }

    /**
     * Returns a array of warps the player can use
     *
     * @param Player $player
     * @return array<Warp>
     */
    public function getAllowedWarps(Player $player) : array {
        $warps = [];
        foreach ($this->warps as $warp) {
            if (!$warp->canWarp($player)) continue;
            $warps[] = $warp;
        }
        return $warps;
    }

}