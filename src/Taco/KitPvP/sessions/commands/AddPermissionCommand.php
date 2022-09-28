<?php namespace Taco\KitPvP\sessions\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;
use Taco\KitPvP\Manager;

class AddPermissionCommand extends Command {

    public function __construct() {
        parent::__construct("addpermission", "Add permission to a player.");
        $this->setPermission("core.permissions");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!$this->testPermission($sender)) return;
        if (count($args) < 2) {
            $sender->sendMessage(TF::RED . "Incorrect usage. Proper usage: /addpermission (player) (permission)");
            return;
        }
        if (is_null($player = Server::getInstance()->getPlayerByPrefix($args[0]))) {
            $sender->sendMessage(TF::RED . "That player is not online, or does not exist.");
            return;
        }
        Manager::getSessionManager()->getSession($player)->addPermission($args[1]);
        $sender->sendMessage(TF::GREEN . "Added permission \"" . $args[1] . "\" to player.");
    }

}