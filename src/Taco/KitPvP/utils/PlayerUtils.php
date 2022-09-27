<?php namespace Taco\KitPvP\utils;

use pocketmine\player\Player;

class PlayerUtils {

    /**
     * Replaces a bunch of things like {name}, {kills} etc for the player
     *
     * @param Player $player
     * @param string $message
     * @return string
     */
    public static function replaceForConfig(Player $player, string $message) : string {
        return str_replace(
            [
                "{name}"
            ],
            [
                $player->getName()
            ],
            $message
        );
    }

}