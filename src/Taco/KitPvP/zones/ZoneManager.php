<?php namespace Taco\KitPvP\zones;

use JsonException;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use pocketmine\world\World;
use Taco\KitPvP\Main;
use Taco\KitPvP\utils\VectorUtils;
use Taco\KitPvP\zones\commands\AddZoneCommand;
use Taco\KitPvP\zones\commands\RemoveZoneCommand;

class ZoneManager {

    /** @var Config */
    private Config $save;

    /** @var array<Zone> */
    private array $zones;

    public function __construct() {
        Server::getInstance()->getCommandMap()->registerAll("KitPvP", [
            new AddZoneCommand(),
            new RemoveZoneCommand()
        ]);

        $this->save = new Config(Main::getInstance()->getDataFolder() . "zones.yml", Config::YAML);
    }

    /**
     * Adds a zone to file and reloads all zones
     *
     * @param string $name
     * @param Vector3 $pos1
     * @param Vector3 $pos2
     * @param World $world
     * @return void
     * @throws JsonException
     */
    public function addZone(string $name, Vector3 $pos1, Vector3 $pos2, World $world) : void {
        $this->save->set($name, [
            "v1" => VectorUtils::vectorToString($pos1),
            "v2" => VectorUtils::vectorToString($pos2),
            "world" => $world->getDisplayName()
        ]);
        $this->save->save();
        $this->reloadZones();
    }

    /**
     * Removes a zone and reloads file
     *
     * @param string $name
     * @return void
     * @throws JsonException
     */
    public function removeZone(string $name) : void {
        $this->save->remove($name);
        $this->save->save();
        $this->reloadZones();
    }


    /**
     * Returns whether a zone with the name exists
     *
     * @param string $name
     * @return bool
     */
    public function zoneExists(string $name) : bool {
        foreach ($this->zones as $zone) {
            if (!$zone->getName() == $name) continue;
            return true;
        }
        return false;
    }

    /**
     * Returns whether the position is in a zone
     *
     * @param Position $pos
     * @return bool
     */
    public function isInZone(Position $pos) : bool {
        foreach ($this->zones as $zone) {
            if ($zone->isInside($pos)) return true;
        }
        return false;
    }

    /**
     * Reloads all zones from file
     *
     * @return void
     */
    public function reloadZones() : void {
        $this->zones = [];
        foreach ($this->save->getAll() as $name => $data) {
            $min = VectorUtils::getMinVector(
                VectorUtils::stringToVector($data["v1"]),
                VectorUtils::stringToVector($data["v2"])
            );
            $max = VectorUtils::getMaxVector(
                VectorUtils::stringToVector($data["v1"]),
                VectorUtils::stringToVector($data["v2"])
            );
            $this->zones[] = new Zone(
                $name,
                $data["world"],
                new AxisAlignedBB(
                    $min->getX(),
                    $min->getY(),
                    $min->getZ(),
                    $max->getX(),
                    $max->getY(),
                    $max->getZ()
                )
            );
        }
    }

}