<?php

namespace DvsaMotApiTest\Service\Mapper;

use DvsaCommon\Enum\MotTestStatusName;
use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\MotTestStatus;
use DvsaMotApi\Service\Mapper\MotTestRecentCertificateMapper;
use PHPUnit_Framework_Assert;
use DvsaEntities\Entity\MotTestRecentCertificate;

/**
 * Class MotTestMapperTest
 */
class MotTestRecentCertificateMapperTest extends \PHPUnit_Framework_TestCase
{
    private $data = [];
    /** @var MotTestRecentCertificate / */
    private $motTestRecentCertificate;
    /** @var MotTestRecentCertificateMapper / */
    private $motTestRecentCertificateMapper;

    public function testMotTestRecentCertificateToDto()
    {
        $this->motTestRecentCertificate->setId($this->data['id']);
        $this->motTestRecentCertificate->setTesterPersonId($this->data['testerId']);
        $this->motTestRecentCertificate->setVin($this->data['vin']);
        $this->motTestRecentCertificate->setRegistration($this->data['registration']);
        $this->motTestRecentCertificate->setCertificateStorageKey($this->data['storageKey']);
        $this->motTestRecentCertificate->setVtsId($this->data['vtsId']);
        $this->motTestRecentCertificate->setDocumentId($this->data['id']);
        $this->motTestRecentCertificate->setCertificateStatus($this->data['certificateStatus']);
        $this->motTestRecentCertificate->setGenerationStartedOn($this->data['generationStarted']);
        $this->motTestRecentCertificate->setGenerationCompletedOn($this->data['generationCompletedOn']);
        $this->motTestRecentCertificate->setModel($this->data['model']);
        $this->motTestRecentCertificate->setMake($this->data['make']);

        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::PASSED);
        $this->motTestRecentCertificate->expects($this->any())->method('getStatus')->will($this->returnValue($status));

        $dto = $this->motTestRecentCertificateMapper->mapMotRecentCertificate($this->motTestRecentCertificate);

        $this->assertSame($this->motTestRecentCertificate->getId(), $dto->getId());
        $this->assertSame($this->motTestRecentCertificate->getTesterPersonId(), $dto->getTesterId());
        $this->assertSame($this->motTestRecentCertificate->getVin(), $dto->getVin());
        $this->assertSame($this->motTestRecentCertificate->getRegistration(), $dto->getRegistration());
        $this->assertSame(
            $this->motTestRecentCertificate->getCertificateStorageKey(),
            $dto->getCertificateStorageKey()
        );
        $this->assertSame($this->motTestRecentCertificate->getVtsId(), $dto->getVtsId());
        $this->assertSame($this->motTestRecentCertificate->getModel(), $dto->getModel());
        $this->assertSame($this->motTestRecentCertificate->getMake(), $dto->getMake());
        $this->assertSame($this->motTestRecentCertificate->getVtsId(), $dto->getVtsId());
        $this->assertSame($this->motTestRecentCertificate->getPrsId(), $dto->getPrsId());
    }

    public function testPassPrsStatus()
    {
        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::PASSED);

        $this->motTestRecentCertificate->expects(
            $this->any())->method('getStatus')->will($this->returnValue($status)
        );

        $this->motTestRecentCertificate->expects(
            $this->any())->method('getPrsId')->will($this->returnValue($this->data['prsId'])
        );

        $this->assertSame(
            MotTestRecentCertificateMapper::MOT_PASS_PRS_STATUS,
            $this->motTestRecentCertificateMapper->getStatus($this->motTestRecentCertificate)
        );
    }

    public function testPassStatus()
    {
        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::PASSED);
        $this->motTestRecentCertificate->expects($this->any())->method('getStatus')->will($this->returnValue($status));
        $this->motTestRecentCertificate->expects($this->any())->method('getPrsId')->will($this->returnValue(null));

        $this->assertSame(
            MotTestRecentCertificateMapper::MOT_PASS_STATUS,
            $this->motTestRecentCertificateMapper->getStatus($this->motTestRecentCertificate)
        );
    }

    public function testFailStatus()
    {
        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::FAILED);
        $this->motTestRecentCertificate->expects($this->any())->method('getStatus')->will($this->returnValue($status));
        $this->motTestRecentCertificate->expects($this->any())->method('getPrsId')->will($this->returnValue(null));

        $this->assertSame(
            MotTestRecentCertificateMapper::MOT_FAIL_STATUS,
            $this->motTestRecentCertificateMapper->getStatus($this->motTestRecentCertificate)
        );
    }

    public function testGetMakeFromId()
    {
        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::PASSED);

        $makeName = 'Car Make From Id';
        $make = $this->data['make'];
        $make->setName($makeName);

        $this->motTestRecentCertificate->expects($this->any())->method('getStatus')->will($this->returnValue($status));
        $this->motTestRecentCertificate->expects($this->any())->method('getMake')->willReturn($make);

        $this->assertSame(
            $make->getName(),
            $this->motTestRecentCertificateMapper->getMake($this->motTestRecentCertificate)
        );
    }

    public function testGetMakeFromMot()
    {
        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::PASSED);

        $makeName = 'Car Make From MOT';

        $this->motTestRecentCertificate->expects($this->any())->method('getStatus')->will($this->returnValue($status));
        $this->motTestRecentCertificate->expects($this->any())->method('getMake')->willReturn(null);
        $this->motTestRecentCertificate->expects($this->any())->method('getMakeName')->willReturn($makeName);

        $this->assertSame(
            $makeName,
            $this->motTestRecentCertificateMapper->getMake($this->motTestRecentCertificate)
        );
    }

    public function testGetModelFromId()
    {
        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::PASSED);

        $modelName = 'Car Model From Id';
        $model = $this->data['model'];
        $model->setName($modelName);

        $this->motTestRecentCertificate->expects($this->any())->method('getStatus')->will($this->returnValue($status));
        $this->motTestRecentCertificate->expects($this->any())->method('getModel')->willReturn($model);

        $this->assertSame(
            $model->getName(),
            $this->motTestRecentCertificateMapper->getModel($this->motTestRecentCertificate)
        );
    }

    public function testGetModelFromMot()
    {
        $status = new MotTestStatus();
        $status->setName(MotTestStatusName::PASSED);

        $modelName = 'Car Model From MOT';

        $this->motTestRecentCertificate->expects($this->any())->method('getStatus')->will($this->returnValue($status));
        $this->motTestRecentCertificate->expects($this->any())->method('getModel')->willReturn(null);
        $this->motTestRecentCertificate->expects($this->any())->method('getModelName')->willReturn($modelName);

        $this->assertSame(
            $modelName,
            $this->motTestRecentCertificateMapper->getModel($this->motTestRecentCertificate)
        );
    }

    protected function setup()
    {
        $this->data = [
            'id' => '555',
            'testerId' => '5',
            'vin' => '1M8GDM9AXKP042788',
            'testerPersonId' => '25',
            'registration' => 'FNZ6110',
            'storageKey' => 'b5911c25-14cd-49ad-aed7-083861ddb3ab',
            'vtsId' => '1',
            'documentId' => '99',
            'certificateStatus' => MotTestStatusName::PASSED,
            'generationStarted' => new \DateTime('2015-01-02 00:00:00'),
            'generationCompletedOn' => new \DateTime('2015-01-02 00:00:20'),
            'model' => new Model(),
            'make' => new Make(),
            'prsId' => '32',

        ];

        $this->motTestRecentCertificateMapper = new MotTestRecentCertificateMapper();
        $this->motTestRecentCertificate = $this->getMock(MotTestRecentCertificate::class);
    }
}
