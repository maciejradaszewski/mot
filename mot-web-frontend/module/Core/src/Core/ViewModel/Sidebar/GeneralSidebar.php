<?php

namespace Core\ViewModel\Sidebar;

class GeneralSidebar implements SidebarInterface
{
    /** @var GeneralSidebarItemInterface[] */
    private $sidebarItems = [];

    public function getSidebarItems()
    {
        return $this->sidebarItems;
    }

    public function addItem(GeneralSidebarItemInterface $item)
    {
        $this->sidebarItems[] = $item;
    }

    public function getPartialTemplate()
    {
        return 'partial/gds/general/sideBarItems';
    }
}
