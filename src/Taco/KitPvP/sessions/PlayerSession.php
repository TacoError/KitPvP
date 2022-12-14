<?php namespace Taco\KitPvP\sessions;

use JsonException;
use pocketmine\permission\PermissionAttachment;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Taco\KitPvP\Main;
use Taco\KitPvP\Manager;

class PlayerSession {

    /** @var Player */
    private Player $player;

    /** @var Config */
    private Config $store;

    /** @var array */
    private array $data;

    /** @var array<string, PermissionAttachment> */
    private array $attachments = [];

    public function __construct(Player $player, Config $store) {
        $this->store = $store;
        $this->player = $player;

        if ($store->exists($player->getName())) $this->data = $store->get($player->getName());
        else $this->data = [
            "kills" => 0,
            "deaths" => 0,
            "killStreak" => 0,
            "bestKillStreak" => 0,
            "group" => "Guest",
            "kitCoolDowns" => [],
            "permissions" => []
        ];

        $this->reloadPermissions();
    }

    /**
     * Ran when the player is killed
     *
     * @return void
     */
    public function died() : void {
        $this->data["killStreak"] = 0;
        $this->data["deaths"]++;
    }

    /**
     * Ran when the player kills another player
     *
     * @return void
     */
    public function killed() : void {
        $this->data["killStreak"]++;
        $this->data["kills"]++;
        if ($this->data["killStreak"] > $this->data["bestKillStreak"]) {
            $this->data["bestKillStreak"] = $this->data["killStreak"];
        }
    }

    /**
     * Sets the group for the session and reloads the permissions
     *
     * @param string $name
     * @return void
     */
    public function setGroup(string $name) : void {
        $this->data["group"] = $name;
        $this->reloadPermissions();
    }

    /**
     * Clears all of the players permissions
     *
     * @return void
     */
    public function clearPermissions() : void {
        foreach ($this->getPermissionList() as $permission) {
            if (!$this->player->hasPermission($permission)) continue;
            if (!isset($this->attachments[$permission])) continue;
            $this->player->removeAttachment($this->attachments[$permission]);
        }
        $this->attachments = [];
    }

    /**
     * Reloads all of the players permissions
     *
     * @return void
     */
    public function reloadPermissions() : void {
        $this->clearPermissions();

        foreach ($this->getPermissionList() as $permission) {
            $this->attachments[$permission] = $this->player->addAttachment(Main::getInstance(), $permission, true);
        }
    }

    /**
     * Adds permission to just the player
     *
     * @param string $permission
     * @return void
     */
    public function addPermission(string $permission) : void {
        if (isset($this->data["permissions"][$permission])) return;
        $this->data["permissions"][$permission][] = $permission;
        $this->reloadPermissions();
    }

    /**
     * Remove a permission from just the player
     *
     * @param string $permission
     * @return void
     */
    public function removePermission(string $permission) : void {
        if (!$this->player->hasPermission($permission) || !isset($this->data["permissions"][$permission])) return;
        unset($this->data["permissions"][$permission]);
        $this->reloadPermissions();
    }

    /**
     * Saves this sessions data to a file
     *
     * @return void
     * @throws JsonException
     */
    public function save() : void {
        $this->clearPermissions();

        $this->store->remove($this->player->getName());
        $this->store->set($this->player->getName(), $this->data);
        $this->store->save();
    }

    /**
     * Sets the time for a kit coolDown
     *
     * @param string $name
     * @return void
     */
    public function setKitCoolDown(string $name) : void {
        $this->data["kitCoolDowns"][$name] = time();
    }

    /**
     * Returns a int for the kit coolDown
     *
     * @param string $name
     * @return int
     */
    public function getTimeOnCoolDown(string $name) : int {
        if (!isset($this->data["kitCoolDowns"][$name])) return time();
        return time() - $this->data["kitCoolDowns"][$name];
    }

    /**
     * Returns all of the players permissions (including ones inherited from groups)
     *
     * @return array
     */
    public function getPermissionList() : array {
        return array_merge(
            $this->data["permissions"],
            Manager::getGroupManager()->getGroupFromName($this->data["group"])->getPermissions()
        );
    }

}