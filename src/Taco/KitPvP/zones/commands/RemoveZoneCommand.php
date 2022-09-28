<?php namespace Taco\KitPvP\zones\commands;

use JsonException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;
use Taco\KitPvP\Manager;

class RemoveZoneCommand extends Command {

    public function __construct() {
        parent::__construct("removezone", "Remove a zone.");
        $this->setPermission("core.zones");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!$this->testPermission($sender)) return;
        if (count($args) < 1) {
            $sender->sendMessage(TF::RED . "Please provide a zone to remove.");
            return;
        }
        $zm = Manager::getZoneManager();
        if (is_null($zm->zoneExists($args[0]))) {
            $sender->sendMessage(TF::RED . "There is no zone with that name.");
            return;
        }
        $zm->removeZone($args[0]);
        $sender->sendMessage(TF::GREEN . "Removed the zone " . $args[0] . ".");
    }

}