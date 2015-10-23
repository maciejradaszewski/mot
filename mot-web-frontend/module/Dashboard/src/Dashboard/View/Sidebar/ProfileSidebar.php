<?php
namespace Dashboard\View\Sidebar;

use Core\View\Sidebar\GeneralSidebar;

class ProfileSidebar extends GeneralSidebar
{
    /**
     * @param int $personId
     */
    public function __construct($personId)
    {
        $url = '/profile/' . $personId . '/trade-roles';
        $relatedLinks = [];
        $relatedLinks [] = ['Roles and associations' => ['id' => 'roles-and-associations-link', 'href' => $url]];

        $relatedList = [
            'type' => 'linkList',
            'title' => 'Related',
            'items' =>  $relatedLinks
        ];

        parent::__construct([$relatedList]);
    }
}
