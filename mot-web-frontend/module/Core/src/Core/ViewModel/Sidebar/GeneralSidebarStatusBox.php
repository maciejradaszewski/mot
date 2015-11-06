<?php

namespace Core\ViewModel\Sidebar;

class GeneralSidebarStatusBox implements GeneralSidebarItemInterface
{
    /** @var GeneralSidebarStatusItem[] */
    private $items = [];

    public function getItems()
    {
        return $this->items;
    }

    public function addItem(GeneralSidebarStatusItem $item)
    {
        $this->items[] = $item;
    }
}
