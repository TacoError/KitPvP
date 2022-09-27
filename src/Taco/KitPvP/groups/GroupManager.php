<?php namespace Taco\KitPvP\groups;

use pocketmine\Server;
use Taco\KitPvP\groups\commands\SetGroupCommand;
use Taco\KitPvP\Main;

class GroupManager {

    /** @var array<Group> */
    private array $groups = [];

    public function __construct() {
        Server::getInstance()->getCommandMap()->registerAll("KitPvP", [
            new SetGroupCommand()
        ]);

        foreach (Main::$config["groups"] as $name => $data) {
            $this->groups[] = new Group($name, $data["fancyName"], $data["permissions"]);
        }
    }

    /**
     * If the group exists, it returns it, otherwise null
     *
     * @param string $name
     * @return Group|null
     */
    public function getGroupFromName(string $name) : ?Group {
        foreach ($this->groups as $group) {
            if (
                $group->getName() == $name ||
                $group->getFancyName() == $name
            ) return $group;
        }
        return null;
    }

}