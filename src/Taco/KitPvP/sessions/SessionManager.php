<?php namespace Taco\KitPvP\sessions;

use JsonException;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Taco\KitPvP\Main;

class SessionManager {

    /** @var array<string, PlayerSession> */
    private array $sessions = [];

    /** @var Config */
    private Config $playerStore;

    public function __construct() {
        $this->playerStore = new Config(Main::getInstance()->getDataFolder() . "store.yml", Config::YAML);
    }

    /**
     * Returns the players session
     *
     * @param Player $player
     * @return PlayerSession
     */
    public function getSession(Player $player) : PlayerSession {
        return $this->sessions[$player->getName()];
    }

    /**
     * Opens a new player session
     *
     * @param Player $player
     * @return void
     */
    public function openSession(Player $player) : void {
        $this->sessions[$player->getName()] = new PlayerSession($player, $this->playerStore);
    }

    /**
     * Closes a player session
     *
     * @param Player $player
     * @return void
     * @throws JsonException
     */
    public function closeSession(Player $player) : void {
        $this->sessions[$player->getName()]->save();
        unset($this->sessions[$player->getName()]);
    }


}