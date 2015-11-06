<?php

namespace Core\ViewModel\Sidebar;

use DvsaCommon\Utility\TypeCheck;

class GeneralSidebarLinkList implements GeneralSidebarItemInterface
{
    private $title;

    /** @var GeneralSidebarLink[] */
    private $links;

    function __construct($title)
    {
        $this->title = $title;
    }

    public function addLink(GeneralSidebarLink $link)
    {
        TypeCheck::assertInstance($link, GeneralSidebarLink::class);
        $this->links[] = $link;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function isEmpty()
    {
        return $this->links ? false : true;
    }
}
