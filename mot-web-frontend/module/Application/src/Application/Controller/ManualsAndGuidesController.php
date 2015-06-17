<?php

namespace Application\Controller;

use Core\Controller\AbstractAuthActionController;

/**
 * Controller static page with list of links to GOV websites with manuals, guides and standards
 */
class ManualsAndGuidesController extends AbstractAuthActionController
{
    const ROUTE = 'manuals';

    const PAGE_TITLE_INDEX_ACTION = 'Manuals and guides';
    const PAGE_SUBTITLE_INDEX_ACTION = 'Resources';

    public function indexAction()
    {
        $this->layout()->setVariables([
            'pageTitle' => self::PAGE_TITLE_INDEX_ACTION,
            'pageSubTitle' => self::PAGE_SUBTITLE_INDEX_ACTION,
        ]);

        return [
            'resourceLinks' => $this->getResourceLinks(),
        ];
    }

    private function getResourceLinks()
    {
        $resourceLinks = [
            [
                "name" => "MOT inspection manual for class 1 and 2 vehicles",
                "url" => "http://www.motinfo.gov.uk/htdocs/m1i00000001.htm",
                "help_text" => "Manual for motor bicycle and side car testing",
            ],
            [
                "name" => "MOT inspection manual for class 3, 4, 5, and 7 vehicles",
                "url" => "http://www.motinfo.gov.uk/htdocs/m4i00000001.htm",
                "help_text" => "Manual for testing private passenger and light commercial vehicles",
            ],
            [
                "name" => "MOT testing guide",
                "url" => "http://www.motinfo.gov.uk/htdocs/tgi00000001.htm",
                "help_text" => "Guidance on how the MOT scheme is run",
            ],
            [
                "name" => "In service exhaust emission standards for road vehicles: 18th edition",
                "url" => "https://www.gov.uk/government/uploads/system/uploads/attachment_data/file/348035/18th-edition-emissions-book-complete.pdf",
                "help_text" => "Standards for checking vehicle exhaust emission procedures and limits",
            ]
        ];

        return $resourceLinks;
    }
}
