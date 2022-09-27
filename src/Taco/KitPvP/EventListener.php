<?php namespace Taco\KitPvP;

use JsonException;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use Taco\KitPvP\utils\PlayerUtils;

class EventListener implements Listener {

    public function onLogin(PlayerLoginEvent $event) : void {
        Manager::getSessionManager()->openSession($event->getPlayer());
    }

    public function onJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();

        $event->setJoinMessage(PlayerUtils::replaceForConfig($player, Main::$config["join"]["message-server"]));
        $player->sendMessage(PlayerUtils::replaceForConfig($player, join("\n", Main::$config["join"]["message-player"])));
        $player->sendTitle(
            PlayerUtils::replaceForConfig($player, Main::$config["join"]["title"]),
            PlayerUtils::replaceForConfig($player, Main::$config["join"]["subtitle"])
        );

        $player->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
        $player->setHealth(20);
        $player->getHungerManager()->setFood(20);
    }

    /*** @throws JsonException */
    public function onQuit(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
        $event->setQuitMessage(PlayerUtils::replaceForConfig($player, Main::$config["quit-message"]));
        Manager::getSessionManager()->closeSession($player);
    }



}