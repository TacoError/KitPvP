<?php namespace Taco\KitPvP\zones\commands;

use JsonException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use Taco\KitPvP\Manager;

class AddZoneCommand extends Command {

    /** @var Vector3 */
    private Vector3 $pos1;

    /** @var Vector3 */
    private Vector3 $pos2;

    public function __construct() {
        parent::__construct("addzone", "Create zone.");
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
        if (!$this->testPermission($sender) || !$sender instanceof Player) return;
        if (!count($args) > 0) {
            $sender->sendMessage(TF::RED . "Incorrect usage. Correct usage: /addzone [pos1, pos2, create (name)]");
            return;
        }
        if ($args[0] == "pos1") {
            $this->pos1 = $sender->getPosition()->asVector3();
            $sender->sendMessage(TF::GREEN . "Position one set.");
            return;
        }
        if ($args[0] == "pos2") {
            $this->pos2 = $sender->getPosition()->asVector3();
            $sender->sendMessage(TF::GREEN . "Position two set.");
            return;
        }
        if ($args[0] == "create") {
            if (count($args) < 2) {
                $sender->sendMessage(TF::RED . "Please provide a name for the area.");
                return;
            }
            Manager::getZoneManager()->addZone($args[1], $this->pos1, $this->pos2, $sender->getWorld());
            $sender->sendMessage(TF::GREEN . "Added zone!");
        }
    }

}