<?php
/***
 * Simple class which converts MotTest status to
 * required value for the MOT certificates list. Gets the make and
 * model name from the related object, or failing that, the string stored
 * against the MOT test itself.
 */
namespace DvsaMotApi\Service\Mapper;

use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Dto\Common\MotTestRecentCertificatesDto;
use DvsaEntities\Entity\MotTestRecentCertificate;
use Zend\Stdlib\DateTime;


/**
 * Class MotTestMapper
 */
class MotTestRecentCertificateMapper
{

    const MOT_PASS_STATUS = 'Pass';
    const MOT_PASS_PRS_STATUS = 'Pass PRS';
    const MOT_FAIL_STATUS = 'Fail';

    /**
     * @param MotTestRecentCertificate $motTest
     * @return MotTestRecentCertificatesDto
     */
    public function mapMotRecentCertificate(MotTestRecentCertificate $motTest)
    {
        $status = $this->getStatus($motTest);
        $make = $this->getMake($motTest);
        $model = $this->getModel($motTest);

        $certDto = new MotTestRecentCertificatesDto();
        $certDto->setId($motTest->getId());
        $certDto->setTesterId($motTest->getTesterPersonId());
        $certDto->setVtsId($motTest->getVtsId());
        $certDto->setModel($model);
        $certDto->setVin($motTest->getVin());
        $certDto->setMake($make);
        $certDto->setRegistration($motTest->getRegistration());
        $certDto->setMotTestResult($status);
        $certDto->setCertificateStorageKey($motTest->getCertificateStorageKey());
        $certDto->setStatusCode($motTest->getStatus()->getCode());
        if ($motTest->getGenerationCompletedOn() instanceof \DateTime) {
            $certDto->setGenerationCompletedOn($motTest->getGenerationCompletedOn()->getTimestamp());
        }
        $certDto->setRecipientFirstName($motTest->getRecipientFirstName());
        $certDto->setRecipientFamilyName($motTest->getRecipientFamilyName());
        $certDto->setRecipientEmailAddress($motTest->getRecipientEmail());

        return $certDto;
    }

    /**
     * @param MotTestRecentCertificate $motTest
     * @return int
     */
    public function getModel(MotTestRecentCertificate $motTest)
    {
        if (!$motTest->getModel()) {
            $model = $motTest->getModelName();
        } else {
            $model = $motTest->getModel()->getName();
        }

        return $model;
    }

    public function getMake(MotTestRecentCertificate $motTest)
    {
        if (!$motTest->getMake()) {
            $make = $motTest->getMakeName();
        } else {
            $make = $motTest->getMake()->getName();
        }

        return $make;
    }

    /**
     * @param MotTestRecentCertificate $motTest
     * @return mixed|string
     */
    public function getStatus(MotTestRecentCertificate $motTest)
    {
        switch ($motTest->getStatus()->getName()) {
            case MotTestStatusName::PASSED:
                if (!$motTest->getPrsId()) {
                    $status = self::MOT_PASS_STATUS;
                } else {
                    $status = self::MOT_PASS_PRS_STATUS;
                }
                break;

            case MotTestStatusName::FAILED:
                $status = self::MOT_FAIL_STATUS;
                break;

            default:
                $status = $motTest->getStatus();
                break;
        }

        return $status;
    }

}
