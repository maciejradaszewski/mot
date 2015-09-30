<?php
namespace DvsaMotTestTest\View\Model;

use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Common\MotTestTypeDto;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaMotTest\View\Model\MotTestTitleModel;

class MotTestTitleModelTest extends \PHPUnit_Framework_TestCase
{

    const MOT_TESTING_TITLE = 'MOT testing';
    const MOT_TRAINING_TEST_TITLE = 'Training test';

    /** @var MotTestTitleModel */
    private $motTestTitleModel;

    public function setUp()
    {
        $this->motTestTitleModel = new MotTestTitleModel();
    }

    public function testMotTestIsNotAnyTypeReturnsDefaultTitle()
    {
        $title = $this->motTestTitleModel->getTitle(null);
        $this->assertEquals(self::MOT_TESTING_TITLE, $title);
    }

    public function testMotTestIsNotTrainingReturnsDefaultTitle()
    {
        $motTest = new MotTestDto();
        $motTest->setTestType(
            (new MotTestTypeDto())->setCode(MotTestTypeCode::NORMAL_TEST)
        );

        $title = $this->motTestTitleModel->getTitle($motTest);
        $this->assertEquals(self::MOT_TESTING_TITLE, $title);
    }

    public function testMotTestIsTrainingReturnsTrainingTitle()
    {
        $motTest = new MotTestDto();
        $motTest->setTestType(
            (new MotTestTypeDto())->setCode(MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING)
        );

        $title = $this->motTestTitleModel->getTitle($motTest);
        $this->assertEquals(self::MOT_TRAINING_TEST_TITLE, $title);
    }

}
