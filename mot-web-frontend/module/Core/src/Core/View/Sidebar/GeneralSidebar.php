<?php

namespace Core\View\Sidebar;

class GeneralSidebar implements SidebarInterface
{
    private $sidebarItems;

    public function __construct(array $sidebarItems)
    {
        $this->sidebarItems = $sidebarItems;
    }

    public function getSidebarItems()
    {
        return $this->sidebarItems;
    }

    public function getPartialTemplate()
    {
        return 'partial/gds/general/sideBarItems';
    }
}
