<?php namespace Taco\KitPvP\groups\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;
use Taco\KitPvP\Main;
use Taco\KitPvP\Manager;

class SetGroupCommand extends Command {

    public function __construct() {
        parent::__construct("setgroup", "Set a players group.");
        $this->setPermission("core.groups");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!$this->testPermission($sender)) return;
        if (count($args) < 2) {
            $sender->sendMessage(TF::RED . "Incorrect usage. Proper usage: /setgroup (playerName) (groupName)");
            return;
        }
        if (is_null($player = Server::getInstance()->getPlayerByPrefix($args[0]))) {
            $sender->sendMessage(TF::RED . "That player is not online, or does not exist.");
            return;
        }
        if (is_null($group = Manager::getGroupManager()->getGroupFromName($args[1]))) {
            $sender->sendMessage(TF::RED . "There is no group with that name.");
            return;
        }
        Manager::getSessionManager()->getSession($player)->setGroup($group->getName());
        $sender->sendMessage(TF::GREEN . "Set players group to: " . $group->getFancyName());
        $player->sendMessage(str_replace("{group}", $group->getFancyName(), Main::$config["commands"]["setgroup"]["group-set"]));
    }

}