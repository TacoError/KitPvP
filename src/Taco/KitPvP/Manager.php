<?php namespace Taco\KitPvP;

use Taco\KitPvP\groups\GroupManager;
use Taco\KitPvP\sessions\SessionManager;
use Taco\KitPvP\zones\ZoneManager;

class Manager {

    /** @var SessionManager */
    private static SessionManager $sessionManager;

    /** @var ZoneManager */
    private static ZoneManager $zoneManager;

    /** @var GroupManager */
    private static GroupManager $groupManager;

    public function __construct() {
        self::$sessionManager = new SessionManager();
        self::$zoneManager = new ZoneManager();
        self::$groupManager = new GroupManager();
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

}