<?php

namespace DvsaMotEnforcement\Model;

use Application\Service\CatalogService;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Date\DateUtils;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class MotTest
 *
 * @package DvsaMotEnforcement\Model
 */
class MotTest
{
    const DVSA_SHORT_DATE_FORMAT = 'd M Y H:i';

    public function translateMotTestStatusForDisplay($dbStatus)
    {
        switch ($dbStatus) {
            case 'FAILED':
                return 'FAIL';
            case 'PASSED':
                return 'PASS';
            case 'ACTIVE':
                return 'IN PROGRESS';
            default:
                return $dbStatus;
        }
    }

    public function prepareDataForVehicleExaminerListRecentMotTestsView(
        $inputData,
        $viewRender,
        CatalogService $catalog
    ) {
        if (!is_array($inputData)) {
            throw new \InvalidArgumentException('inputData should be an array');
        }

        $outputData = [];

        foreach ($inputData as $motTestNumber => $motTest) {
            $status = (!empty($motTest['status']) ? $motTest['status'] : false);
            if ($status) {
                // Change the FAILED to FAIL, PASSED to PASS
                $motTest['display_status'] = $this->translateMotTestStatusForDisplay($status);
            }

            // VM-1674: Change VRM to "N/A" if tested without a registration mark.
            $hasReg = $motTest['hasRegistration'];
            if (!$hasReg) {
                $motTest['registration'] = 'N/A';
            }

            $testDate = isset($motTest['testDate']) ? $motTest['testDate'] : null;
            $motTest['test_date'] = $testDate ?: "";
            $motTest['display_date'] = DateTimeDisplayFormat::textDateTimeShort($testDate);
            $motTest['display_test_type'] = $motTest['testType'];
            $motTest['popover'] = $this->preparePopover($motTest, $viewRender);

            $outputData[$motTestNumber] = $motTest;
        }

        return $outputData;
    }

    private function preparePopover($motTest, PhpRenderer $viewRender)
    {
        $startedDate = $motTest['startedDate'];
        $completedDate = $motTest['completedDate'];

        if ($startedDate && $completedDate && $startedDate != $completedDate) {
            $startedDate = DateUtils::toDateTime($startedDate);
            $completedDate = DateUtils::toDateTime($completedDate);

            $durationTime = ceil(DateUtils::getDatesTimestampDelta($completedDate, $startedDate) / 60) . ' min';
        } else {
            $durationTime = 'N/A';
        }

        $layout = new ViewModel();
        $layout->setTemplate("motTestPopover");
        $layout->setVariables(['motTest' => $motTest, 'durationTime' => $durationTime]);

        return $viewRender->render($layout);
    }
}
