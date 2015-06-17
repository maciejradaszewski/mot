<?php

namespace DvsaMotApiTest\Service;

use Doctrine\Common\Collections\ArrayCollection;
use DvsaCommon\Enum\ReasonForRejectionTypeName;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaEntities\Entity\Language;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestReasonForRejection;
use DvsaEntities\Entity\ReasonForRejection;
use DvsaMotApi\Service\MotTestCreationHelper;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;

/**
 * Class MotTestCreationHelperTest.
 */
class MotTestCreationHelperTest extends AbstractServiceTestCase
{
    public function testSaveRfrsForRetest()
    {
        //given
        $rfrFail = $this->prepareMotTestRfrEntity(
            1, ReasonForRejectionTypeName::FAIL,
            'RFR fail example'
        );

        $rfrPrs = $this->prepareMotTestRfrEntity(
            1, ReasonForRejectionTypeName::PRS,
            'RFR PRS example'
        );

        $rfrAdv = $this->prepareMotTestRfrEntity(
            1, ReasonForRejectionTypeName::ADVISORY,
            'RFR Advisory example'
        );

        $motTestOrig = new MotTest();
        $motTestOrig->addMotTestReasonForRejection($rfrFail);
        $motTestOrig->addMotTestReasonForRejection($rfrPrs);
        $motTestOrig->addMotTestReasonForRejection($rfrAdv);

        $motTestNew = new MotTest();

        $motTestServiceTest = new MotTestServiceTest();
        $mocks              = $motTestServiceTest->getMocksForMotTestService();

        $mockEntityManager     = $mocks['mockEntityManager'];
        $motTestCreationHelper = new MotTestCreationHelper(
            $mockEntityManager,
            $mocks['mockAuthService'],
            $mocks['mockTesterService'],
            null,
            $mocks['mockMotTestValidator'],
            $motTestServiceTest->getMockWithDisabledConstructor(RetestEligibilityValidator::class),
            $mocks['mockOtpService']
        );

        $mockEntityManager->expects($this->any())
            ->method('persist')
            ->will($this->returnValue(null));

        //when
        $motTestCreationHelper->saveRfrsForRetest($motTestOrig, $motTestNew);

        /** @var MotTestReasonForRejection[] $retestReasons */
        $retestReasons = $motTestNew->getMotTestReasonForRejections();
        $this->assertEquals(2, count($retestReasons));

        foreach ($retestReasons as $retestReason) {
            $this->assertTrue($retestReason->getType() != ReasonForRejectionTypeName::PRS);
        }
    }

    /**
     * @param $id
     * @param $type
     * @param $name
     *
     * @return \DvsaEntities\Entity\MotTestReasonForRejection
     */
    protected function prepareMotTestRfrEntity($id, $type, $name)
    {
        $rfr = new ReasonForRejection();
        $rfr->setDescriptions(
            new ArrayCollection(
                [
                    (new \DvsaEntities\Entity\ReasonForRejectionDescription())
                        ->setLanguage((new Language())->setCode('EN'))
                        ->setName($name),
                ]
            )
        );

        $motTestRfr = new MotTestReasonForRejection();
        $motTestRfr->setId($id);
        $motTestRfr->setType($type);
        $motTestRfr->setReasonForRejection($rfr);

        return $motTestRfr;
    }
}
