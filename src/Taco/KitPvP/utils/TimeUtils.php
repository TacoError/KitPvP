<?php namespace Taco\KitPvP\utils;

class TimeUtils {

    public static function intToHhMmSs(int $time) : string {
        return sprintf(
            "%02d:%02d:%02d",
            ($time/ 3600),
            ($time / 60 % 60),
            $time % 60
        );
    }

}