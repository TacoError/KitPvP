<?php namespace Taco\KitPvP;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {

    use SingletonTrait;

    /** @var array<string, string> */
    public static array $config;

    /*** @return void */
    public function onLoad() : void {
        self::setInstance($this);
    }

    /*** @return void */
    public function onEnable() : void {
        self::$config = $this->getConfig()->getAll();

        new Manager();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

}