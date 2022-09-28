<?php namespace Taco\KitPvP\kits\commands;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Taco\KitPvP\Main;
use Taco\KitPvP\Manager;
use Taco\KitPvP\utils\TimeUtils;

class KitCommand extends Command {

    public function __construct() {
        parent::__construct("kit", "Open the kits form.");
        $this->setAliases(["kits"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!$sender instanceof Player) return;
        $available = Manager::getKitManager()->getAllowedKits($sender);
        $form = new SimpleForm(function(Player $player, ?int $data) use($available) : void {
            if (is_null($data)) return;
            if ($data > (count($available) - 1)) return;
            $kit = $available[$data];
            if ($kit->getCoolDown($player) > 0) {
                $player->sendMessage(Main::$config["kits"]["still-on-cooldown"]);
                return;
            }
            $kit->equip($player);
        });
        $form->setTitle("Kits");
        $form->setContent("Select a kit.");
        foreach ($available as $kit) {
            $cd = "CoolDown: " . ($kit->getCoolDown($sender) > 0 ? TimeUtils::intToHhMmSs($kit->getCoolDown($sender)) : "None");
            $form->addButton($kit->getName() . "\n" . $cd);
        }
        $form->addButton("Close form.");
        $sender->sendForm($form);
    }

}