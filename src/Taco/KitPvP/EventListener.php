<?php namespace Taco\KitPvP;

use JsonException;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
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

    public function onDamage(EntityDamageByEntityEvent $event) : void {
        $hit = $event->getEntity();
        $killer = $event->getDamager();
        if (!$killer instanceof Player || !$hit instanceof Player) return;
        $zm = Manager::getZoneManager();
        if ($zm->isInZone($hit->getPosition()) && $zm->isInZone($killer->getPosition())) return;
        $event->cancel();
        $killer->sendMessage(Main::$config["zones"]["cannot-hit"]);
    }

    public function onDeath(PlayerDeathEvent $event) : void {
        $player = $event->getPlayer();
        Manager::getSessionManager()->getSession($player)->died();
        $cause = $player->getLastDamageCause();
        if (!$cause instanceof EntityDamageByEntityEvent) {
            $event->setDeathMessage(PlayerUtils::replaceForConfig($player, Main::$config["death"]["unknown-reason"]));
            return;
        }
        $killer = $cause->getDamager();
        if (!$killer instanceof Player) {
            $event->setDeathMessage(PlayerUtils::replaceForConfig($player, Main::$config["death"]["unknown-reason"]));
            return;
        }
        Manager::getSessionManager()->getSession($killer)->killed();
        $event->setDeathMessage(str_replace(
            ["{name}", "{killer}"],
            [$player->getName(), $killer->getName()],
            Main::$config["death"]["killed"]
        ));
    }

    public function onPlace(BlockPlaceEvent $event) : void {
        $player = $event->getPlayer();
        if ($player->hasPermission("core.build")) return;
        $event->cancel();
    }

    public function onBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        if ($player->hasPermission("core.build")) return;
        $event->cancel();
    }

}