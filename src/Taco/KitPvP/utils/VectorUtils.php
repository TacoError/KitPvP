<?php namespace Taco\KitPvP\utils;

use pocketmine\math\Vector3;

class VectorUtils {

    /**
     * Returns a vector of the smaller values between the two
     *
     * @param Vector3 $v1
     * @param Vector3 $v2
     * @return Vector3
     */
    public static function getMinVector(Vector3 $v1, Vector3 $v2) : Vector3 {
        return new Vector3(
            min($v1->getX(), $v2->getX()),
            min($v1->getY(), $v2->getY()),
            min($v1->getZ(), $v2->getZ())
        );
    }

    /**
     * Returns a vector of the bigger values between the two
     *
     * @param Vector3 $v1
     * @param Vector3 $v2
     * @return Vector3
     */
    public static function getMaxVector(Vector3 $v1, Vector3 $v2) : Vector3 {
        return new Vector3(
            max($v1->getX(), $v2->getX()),
            max($v1->getY(), $v2->getY()),
            max($v1->getZ(), $v2->getZ())
        );
    }

    /**
     * Turns a vector into a string
     *
     * @param Vector3 $vector
     * @return string
     */
    public static function vectorToString(Vector3 $vector) : string {
        return $vector->getFloorX() . ":" . $vector->getFloorY() . ":" . $vector->getFloorZ();
    }

    /**
     * Turns a stringed vector back into a vector
     *
     * @param string $vector
     * @return Vector3
     */
    public static function stringToVector(string $vector) : Vector3 {
        $vector = explode(":", $vector);
        return new Vector3(
            (int)$vector[0],
            (int)$vector[1],
            (int)$vector[2]
        );
    }

}