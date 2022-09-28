<?php namespace Taco\KitPvP\kits;

use pocketmine\item\Item;
use pocketmine\player\Player;
use Taco\KitPvP\Main;
use Taco\KitPvP\Manager;

class Kit {

    /** @var string */
    private string $name;

    /** @var string */
    private string $description;

    /** @var array<Item> */
    private array $items;

    /** @var string|null */
    private ?string $permission;

    /** @var int */
    private int $coolDown;

    public function __construct(string $name, string $description, array $items, int $coolDown, ?string $permission = null) {
        $this->name = $name;
        $this->description = $description;
        $this->items = $items;
        $this->permission = $permission;
        $this->coolDown = $coolDown;
    }

    /**
     * Returns whether the specified player can equip the kit
     *
     * @param Player $player
     * @return bool
     */
    public function canEquip(Player $player) : bool {
        return is_null($this->permission) || $player->hasPermission($this->permission);
    }

    /**
     * Returns the time left the player has on their coolDown in seconds
     *
     * @param Player $player
     * @return int
     */
    public function getCoolDown(Player $player) : int {
        $time = Manager::getSessionManager()->getSession($player)->getTimeOnCoolDown($this->name);
        return $this->coolDown - $time;
    }

    /*** @return string */
    public function getName() : string {
        return $this->name;
    }

    /*** @return string */
    public function getDescription() : string {
        return $this->description;
    }

    /**
     * Equips the kit for the player
     *
     * @param Player $player
     * @return void
     */
    public function equip(Player $player) : void {
        Manager::getSessionManager()->getSession($player)->setKitCoolDown($this->name);
        $ground = false;
        foreach ($this->items as $item) {
            if (!$player->getInventory()->canAddItem($item)) {
                $ground = true;
                $player->getWorld()->dropItem($player->getPosition()->asVector3(), $item);
                continue;
            }
            $player->getInventory()->addItem($item);
        }
        if (!$ground) return;
        $player->sendMessage(Main::$config["kits"]["drop-ground"]);
    }

}