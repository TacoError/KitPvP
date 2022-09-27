<?php namespace Taco\KitPvP\zones;

use pocketmine\math\AxisAlignedBB;
use pocketmine\world\Position;

class Zone {

    /** @var AxisAlignedBB */
    private AxisAlignedBB $bb;

    /** @var string */
    private string $name;

    /** @var string */
    private string $world;

    public function __construct(string $name, string $world, AxisAlignedBB $bb) {
        $this->bb = $bb;
        $this->name = $name;
        $this->world = $world;
    }

    /**
     * Returns the whether the provided position is in a zone
     *
     * @param Position $pos
     * @return bool
     */
    public function isInside(Position $pos) : bool {
        if (!$pos->getWorld()->getDisplayName() == $this->world) return false;
        return $this->bb->isVectorInside($pos->asVector3());
    }

    /**
     * Returns the world name that the zone is in
     *
     * @return string
     */
    public function getWorld() : string {
        return $this->world;
    }

    /**
     * Returns the name of the zone
     *
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

}