<?php namespace Taco\KitPvP;

use Taco\KitPvP\groups\GroupManager;
use Taco\KitPvP\kits\KitManager;
use Taco\KitPvP\sessions\SessionManager;
use Taco\KitPvP\warps\WarpManager;
use Taco\KitPvP\zones\ZoneManager;

class Manager {

    /** @var SessionManager */
    private static SessionManager $sessionManager;

    /** @var ZoneManager */
    private static ZoneManager $zoneManager;

    /** @var GroupManager */
    private static GroupManager $groupManager;

    /** @var KitManager */
    private static KitManager $kitManager;

    /** @var WarpManager */
    private static WarpManager $warpManager;

    public function __construct() {
        self::$sessionManager = new SessionManager();
        self::$zoneManager = new ZoneManager();
        self::$groupManager = new GroupManager();
        self::$kitManager = new KitManager();
        self::$warpManager = new WarpManager();
    }

    /*** @return SessionManager */
    public static function getSessionManager() : SessionManager {
        return self::$sessionManager;
    }

    /*** @return ZoneManager */
    public static function getZoneManager() : ZoneManager {
        return self::$zoneManager;
    }

    /*** @return GroupManager */
    public static function getGroupManager() : GroupManager {
        return self::$groupManager;
    }

    /*** @return KitManager */
    public static function getKitManager() : KitManager {
        return self::$kitManager;
    }

    /*** @return WarpManager */
    public static function getWarpManager() : WarpManager {
        return self::$warpManager;
    }

}