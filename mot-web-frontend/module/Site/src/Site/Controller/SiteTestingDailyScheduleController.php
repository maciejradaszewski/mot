<?php

namespace Site\Controller;

use DvsaClient\Entity\SiteDailyOpeningHours;
use Core\Controller\AbstractAuthActionController;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use Site\Traits\SiteServicesTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class SiteTestingDailyScheduleController
 *
 * @package Site\Controller
 */
class SiteTestingDailyScheduleController extends AbstractAuthActionController
{

    use SiteServicesTrait;

    const ROUTE_SITE_OPENING_HOURS = 'site/edit-opening-hours';

    public function editAction()
    {
        $errorData = [];
        $siteId = $this->params()->fromRoute('siteId');

        $this->getAuthorizationService()->assertGrantedAtSite(PermissionAtSite::TESTING_SCHEDULE_UPDATE, $siteId);

        $mapperFactory = $this->getMapperFactory();
        $vtsData = $mapperFactory->VehicleTestingStation->getById($siteId);

        $data = $vtsData['siteOpeningHours'];
        $data = $this->extractOpeningHours($data);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            try {
                $mapperFactory->VehicleTestingStationOpeningHours->update($siteId, $data);
                $this->addInfoMessages('Opening times successfully updated');
                return $this->redirect()->toRoute('vehicle-testing-station', ['id' => $siteId]);
            } catch (ValidationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
                $errorData = $e->getExpandedErrorData();
                $this->addFormErrorMessagesToSession($e->getFormErrorDisplayMessages());
            } catch (RestApplicationException $e) {
                $this->addErrorMessages($e->getDisplayMessages());
            }
        }
        return [
            'siteId'           => $siteId,
            'siteOpeningHours' => $data,
            'errorData'        => $errorData,
        ];
    }

    /**
     * @param SiteDailyOpeningHours[] $data
     *
     * @return Array $extractedData
     */
    private function extractOpeningHours($data)
    {
        $extractedData = [];

        for ($i = 0; $i < 7; $i++) {
            $idPrefix = strtolower($data[$i]->getDayName());
            $isClosed = $data[$i]->isClosed();

            $openTime = $isOpenTimeAm = $closeTime = $isCloseTimeAm = null;

            if (!$isClosed) {
                $openTime = $data[$i]->getOpenTime()->format('g.i');
                $closeTime = $data[$i]->getCloseTime()->format('g.i');
                $isOpenTimeAm = $data[$i]->getOpenTime()->isAm();
                $isCloseTimeAm = $data[$i]->getCloseTime()->isAm();
            }

            $extractedData[$idPrefix . 'OpenTime'] = $openTime;
            $extractedData[$idPrefix . 'OpenTimePeriod'] = $isOpenTimeAm ? 'am' : 'pm';
            $extractedData[$idPrefix . 'CloseTime'] = $closeTime;
            $extractedData[$idPrefix . 'CloseTimePeriod'] = $isCloseTimeAm ? 'am' : 'pm';
            $extractedData[$idPrefix . 'IsClosed'] = $data[$i]->isClosed();
        }

        return $extractedData;
    }
}
