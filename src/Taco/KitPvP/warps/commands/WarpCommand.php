<?php namespace Taco\KitPvP\warps\commands;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Taco\KitPvP\Manager;

class WarpCommand extends Command {

    public function __construct() {
        parent::__construct("warp", "Open the warps menu.");
        $this->setAliases(["warps"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!$sender instanceof Player) return;
        $warps = Manager::getWarpManager()->getAllowedWarps($sender);
        $form = new SimpleForm(function(Player $player, ?int $data) use ($warps) {
            if (is_null($data)) return;
            if ($data > (count($warps) - 1)) return;
            $warps[$data]->warp($player);
        });
        $form->setTitle("Warps Menu");
        $form->setContent("Choose a warp to goto.");
        foreach ($warps as $warp) {
            $form->addButton($warp->getName() . "\nTap me to warp!");
        }
        $form->addButton("Close form.");
        $sender->sendForm($form);
    }

}