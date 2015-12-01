<?php

namespace Core\ViewModel\Sidebar;

class SidebarBadge
{
    private $cssClass;

    public function __construct($cssClass)
    {
        $this->cssClass = $cssClass;
    }

    public function getCssClass()
    {
        return $this->cssClass;
    }

    /** @var SidebarBadge */
    private static $normal;

    /** @var SidebarBadge */
    private static $info;

    /** @var SidebarBadge */
    private static $alert;

    /** @var SidebarBadge */
    private static $warning;

    /** @var SidebarBadge */
    private static $success;

    public static function normal()
    {
        self::initializeIfNeeded();
        return self::$normal;
    }

    public static function info()
    {
        self::initializeIfNeeded();
        return self::$info;
    }

    public static function alert()
    {
        self::initializeIfNeeded();
        return self::$alert;
    }

    public static function warning()
    {
        self::initializeIfNeeded();
        return self::$warning;
    }

    public static function success()
    {
        self::initializeIfNeeded();
        return self::$success;
    }

    private static function initializeIfNeeded()
    {
        if (!self::$normal) {
            self::initialize();
        }
    }

    private static function initialize()
    {
        self::$normal = new SidebarBadge('badge');
        self::$alert = new SidebarBadge('badge--alert');
        self::$info = new SidebarBadge('badge--info');
        self::$warning = new SidebarBadge('badge--warn');
        self::$success = new SidebarBadge('badge--success');
    }
}
