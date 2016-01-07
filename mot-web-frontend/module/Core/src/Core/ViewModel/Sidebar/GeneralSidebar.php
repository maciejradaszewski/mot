<?php

namespace Core\ViewModel\Sidebar;

class GeneralSidebar implements SidebarInterface
{
    /**
     * @var GeneralSidebarItemInterface[]
     */
    private $sidebarItems = [];

    /**
     * @return GeneralSidebarItemInterface[]
     */
    public function getSidebarItems()
    {
        return $this->sidebarItems;
    }

    /**
     * @param GeneralSidebarItemInterface $item
     */
    public function addItem(GeneralSidebarItemInterface $item)
    {
        $this->sidebarItems[] = $item;
    }

    /**
     * @return string
     */
    public function getPartialTemplate()
    {
        return 'partial/gds/general/sideBarItems';
    }
}
