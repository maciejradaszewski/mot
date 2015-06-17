<?php
namespace DvsaEntitiesTest\Entity;

use DvsaEntities\Entity\MotTestReasonForRejection;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\ReasonForRejection;

use PHPUnit_Framework_TestCase;

/**
 * Class MotTestReasonForRejectionTest
 */
class MotTestReasonForRejectionTest extends PHPUnit_Framework_TestCase
{
    public function testInitialState()
    {
        $motTestRfr = new MotTestReasonForRejection();

        $this->assertNull(
            $motTestRfr->getId(),
            '"id" should initially be null'
        );
        $this->assertNull(
            $motTestRfr->getMotTest(),
            '"motTest" should initially be null'
        );
        $this->assertNull(
            $motTestRfr->getType(),
            '"type" should initially be null'
        );
        $this->assertNull(
            $motTestRfr->getLocationLateral(),
            '"locationLateral" should initially be null'
        );
        $this->assertNull(
            $motTestRfr->getLocationLongitudinal(),
            '"locationLongitudinal" should initially be null'
        );
        $this->assertNull(
            $motTestRfr->getLocationVertical(),
            '"locationVertical" should initially be null'
        );
        $this->assertNull(
            $motTestRfr->getComment(),
            '"comment" should initially be null'
        );
        $this->assertFalse(
            $motTestRfr->getFailureDangerous(),
            '"failureDangerous" should initially be false'
        );
        $this->assertFalse(
            $motTestRfr->getGenerated(),
            '"generated" should initially be false'
        );
        $this->assertTrue(
            $motTestRfr->getCanBeDeleted(),
            '"canBeDeleted" should initially be true'
        );
    }

    public function testSetsPropertiesCorrectly()
    {
        $motTest = new MotTest();
        $motTestId = 3;
        $rfrId = 1;
        $name = 'Rear Stop lamp';
        $failureText = 'adversely affected by the operation of another lamp';
        $inspectionManualReference = '1.2.1f';
        $type = 'FAIL';
        $locationLateral = 'nearside';
        $locationLongitudinal = 'front';
        $locationVertical = 'top';
        $comment = "Test comment";
        $failureDangerous = true;
        $generated = true;
        $canBeDeleted = !$generated;

        $reasonForRejection = new ReasonForRejection();
        $motTestRfr = new MotTestReasonForRejection();
        $motTestRfr->setMotTest($motTest)
            ->setMotTestId($motTestId)
            ->setReasonForRejection($reasonForRejection)
            ->setType($type)
            ->setLocationLateral($locationLateral)
            ->setLocationLongitudinal($locationLongitudinal)
            ->setLocationVertical($locationVertical)
            ->setComment($comment)
            ->setGenerated($generated)
            ->setFailureDangerous($failureDangerous);

        $this->assertEquals($motTest, $motTestRfr->getMotTest());
        $this->assertEquals($motTestId, $motTestRfr->getMotTestId());
        $this->assertEquals($reasonForRejection, $motTestRfr->getReasonForRejection());
        $this->assertEquals($type, $motTestRfr->getType());
        $this->assertEquals($locationLateral, $motTestRfr->getLocationLateral());
        $this->assertEquals($locationLongitudinal, $motTestRfr->getLocationLongitudinal());
        $this->assertEquals($locationVertical, $motTestRfr->getLocationVertical());
        $this->assertEquals($comment, $motTestRfr->getComment());
        $this->assertEquals($generated, $motTestRfr->getGenerated());
        $this->assertEquals($failureDangerous, $motTestRfr->getFailureDangerous());
        $this->assertEquals($canBeDeleted, $motTestRfr->getCanBeDeleted());
    }

    public static function getTestMotTestReasonForRejection($type = 'FAIL')
    {
        $motTestRfr = new MotTestReasonForRejection();
        $motTestRfr->setType($type);
        return $motTestRfr;
    }

    public static function getTestMotShortTestReasonForRejection()
    {
        return [
            'FAIL'     => ["Rear Stop lamp", "Rear Stop lamp"],
            'PRS'      => ["Rear Stop lamp"],
            'ADVISORY' => ["Rear Stop lamp"],
        ];
    }
}
