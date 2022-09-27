<?php namespace Taco\KitPvP\groups;

class Group {

    /** @var string */
    private string $name;

    /** @var string */
    private string $fancyName;

    /** @var array<string> */
    private array $permissions;

    public function __construct(string $name, string $fancyName, array $permissions) {
        $this->name = $name;
        $this->fancyName = $fancyName;
        $this->permissions = $permissions;
    }

    /**
     * Returns the boring name of the group
     *
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * This will be the name used in things like chat format
     *
     * @return string
     */
    public function getFancyName() : string {
        return $this->fancyName;
    }

    /**
     * Returns this groups permissions
     *
     * @return array
     */
    public function getPermissions() : array {
        return $this->permissions;
    }

}