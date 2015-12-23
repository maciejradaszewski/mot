<?php

namespace Core\ViewModel\Sidebar;

class GeneralSidebarStatusBox implements GeneralSidebarItemInterface
{
    /** @var GeneralSidebarStatusItem[] */
    private $items = [];
    private $id;

    public function getItems()
    {
        return $this->items;
    }

    public function addItem(GeneralSidebarStatusItem $item)
    {
        $this->items[] = $item;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}
