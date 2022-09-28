<?php namespace Taco\KitPvP\kits;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\Server;
use Taco\KitPvP\kits\commands\KitCommand;
use Taco\KitPvP\Main;

class KitManager {

    /** @var array<Kit> */
    private array $kits = [];

    public function __construct() {
        foreach (Main::$config["kits"]["types"] as $name => $data) {
            $this->kits[] = new Kit(
                $name,
                $data["description"] ?? "A kit for KitPvP",
                array_map(fn($item) => $this->constructItem($item), $data["items"]),
                $data["coolDown"] ?? 1,
                $data["permission"] ?? null
            );
        }

        Server::getInstance()->getCommandMap()->registerAll("KitPvP", [
            new KitCommand()
        ]);
    }

    /**
     * Returns a array of kits the player is allowed to use
     *
     * @param Player $player
     * @return array<Kit>
     */
    public function getAllowedKits(Player $player) : array {
        $kits = [];
        foreach ($this->kits as $kit) {
            if (!$kit->canEquip($player)) continue;
            $kits[] = $kit;
        }
        return $kits;
    }

    /**
     * Returns a kit from it's name, if it exists otherwise null
     *
     * @param string $which
     * @return Kit|null
     */
    public function getKitFromName(string $which) : ?Kit {
        foreach ($this->kits as $kit) {
            if ($kit->getName() !== $which) continue;
            return $kit;
        }
        return null;
    }

    /**
     * Makes a item using the format from the config
     *
     * @param array $data
     * @return Item
     */
    public function constructItem(array $data) : Item {
        $item = ItemFactory::getInstance()->get($data["id"], $data["meta"]);
        if (isset($data["custom-name"])) $item->setCustomName($data["custom-name"]);
        if (isset($data["custom-lore"])) $item->setLore($data["custom-lore"]);
        if (isset($data["enchantments"])) {
            foreach ($data["enchantments"] as $eData) {
                $item->addEnchantment(new EnchantmentInstance(
                    EnchantmentIdMap::getInstance()->fromId($eData["id"]),
                    $eData["level"]
                ));
            }
        }
        return $item;
    }

}