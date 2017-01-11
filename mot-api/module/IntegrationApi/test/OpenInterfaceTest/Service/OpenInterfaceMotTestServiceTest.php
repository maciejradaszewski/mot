<?php

namespace MotTestResultTest\Service\Service;

use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaEntities\Entity\Colour;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\DvlaMake;
use DvsaEntities\Entity\DvlaModel;
use DvsaEntities\Entity\DvlaVehicle;
use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\ModelDetail;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestStatus;
use DvsaEntities\Entity\OdometerReading;
use DvsaEntities\Entity\Phone;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Entity\Vehicle;
use IntegrationApi\OpenInterface\Repository\OpenInterfaceMotTestRepository;
use IntegrationApi\OpenInterface\Service\OpenInterfaceMotTestService;

class OpenInterfaceMotTestServiceTest extends AbstractServiceTestCase
{
    const VRM = 'INT001';

    /**
     * @var OpenInterfaceMotTestService
     */
    private $underTest;
    private $mockRepository;

    protected function setUp()
    {
        $this->mockRepository = $this->getMockWithDisabledConstructor(OpenInterfaceMotTestRepository::class);
        $this->underTest = new OpenInterfaceMotTestService($this->mockRepository);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function test_givenMotTestNotFound_throwAnException()
    {
        //given
        $this->mockRepository->expects($this->any())
            ->method('findLatestMotTestForVrm')
            ->will($this->returnValue(null));

        //when
        $this->underTest->getPassMotTestForVehicleIssuedBefore(self::VRM);

        //then an exception is thrown
        $this->fail("NotFoundException should be thrown!");
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\ServiceException
     */
    public function test_givenPassMotTestNotFoundButFailFound_throwAnException()
    {
        $this->mockRepository->expects($this->at(0))
            ->method('findLatestMotTestForVrm')
            ->will($this->returnValue(null));
        $this->mockRepository->expects($this->once())
            ->method('findVehicleByVrm')
            ->will($this->returnValue($this->getMockDvlaVehicle()));
        $this->mockRepository->expects($this->at(1))
            ->method('findLatestMotTestForVrm')
            ->will($this->returnValue($this->prepareMotTest()));

        //when
        $this->underTest->getPassMotTestForVehicleIssuedBefore(self::VRM);

        //then an exception is thrown
        $this->assertTrue(false, "ServiceException(404) should be thrown!");
    }

    public function test_givenPassMotTestFound_shouldReturnPassMotTest()
    {
        //given
        $this->mockRepository->expects($this->once())
            ->method('findLatestMotTestForVrm')
            ->will($this->returnValue($this->getMockMotTest()));

        //when
        $result = $this->underTest->getPassMotTestForVehicleIssuedBefore(self::VRM);

        //then
        $this->assertEquals($this->expectedMotTestPassDetails(), $result);
    }

    public function prepareMotTest()
    {
        $number = "1234567";
        $make = new Make();
        $model = new Model();
        $primaryColour = (new Colour())->setName("Black");
        $odometerReading = new OdometerReading();
        $vts = new Site();
        $status = new MotTestStatus();

        return (new MotTest())
            ->setNumber($number)
            ->setMake($make)
            ->setModel($model)
            ->setPrimaryColour($primaryColour)
            ->setOdometerReading($odometerReading)
            ->setVehicleTestingStation($vts)
            ->setStatus($status)
            ->setVehicle($this->getMockVehicle());
    }

    public function test_givenNoTestsFoundAndVehicleManufacturedPre1960_shouldReturnVehicleDetailsWithDvlaFlags()
    {
        //given
        $this->mockRepository->expects($this->once())
            ->method('findLatestMotTestForVrm')
            ->will($this->returnValue(null));
        $this->mockRepository->expects($this->once())
            ->method('findVehicleByVrm')
            ->will($this->returnValue($this->getMockPre1960Vehicle()));
        $this->mockRepository->expects($this->exactly(2))
            ->method('findColourByCode')
            ->will($this->returnValue($this->getMockColour()));
        $this->mockRepository->expects($this->once())
            ->method('findDvlaMakeByCode')
            ->will($this->returnValue($this->getDvlaMake()));
        $this->mockRepository->expects($this->once())
            ->method('findDvlaModelByMakeCodeModelCode')
            ->will($this->returnValue($this->getDvlaModel()));

        //when
        $result = $this->underTest->getPassMotTestForVehicleIssuedBefore(self::VRM);

        //then
        $this->assertEquals($this->expectedVehicleDetails(), $result);
    }


    private function getMockVehicle()
    {
        $make = new Make();
        $make->setName("FORD");

        $model = new Model();
        $model->setName("MONDEO")
            ->setMake($make);

        $modelDetail = new ModelDetail();
        $modelDetail->setModel($model);

        return (new Vehicle)
            ->setId(1)
            ->setRegistration("GGG455")
            ->setModelDetail($modelDetail)
            ->setColour((new Colour())->setCode("P")->setName("Black"))
            ->setSecondaryColour((new Colour())->setCode("W")->setName("Not Stated"));
    }

    private function getMockDvlaVehicle()
    {
        return (new DvlaVehicle())
            ->setId(1)
            ->setRegistration("GGG455")
            ->setMake((new Make())->setName("FORD"))
            ->setModel((new Model())->setName("MONDEO"))
            ->setPrimaryColour('P')
            ->setSecondaryColour('P')
            ->setMakeCode('FORD')
            ->setModelCode('MONDEO');
    }

    private function getMockPre1960Vehicle()
    {
        $vehicle = $this->getMockDvlaVehicle();
        $vehicle->setManufactureDate((new \DateTime())->setDate(1959, 01, 01));

        return $vehicle;
    }

    private function getMockMotTest()
    {
        $odometerReading = new OdometerReading();
        $odometerReading->setValue(32000);
        $odometerReading->setUnit("mi");


        $phone = new Phone();
        $phone->setNumber("+768-45-4433630");
        $phone->setIsPrimary(true);

        $contactDetail = new ContactDetail();
        $contactDetail->addPhone($phone);

        $siteContactType = new SiteContactType();
        $siteContactType->setCode("BUS");

        $site = new Site();
        $site->setSiteNumber("V1234");
        $site->setContact($contactDetail, $siteContactType);

        return (new MotTest())
            ->setNumber("999999999014")
            ->setIssuedDate((new \DateTime())->setDate(2015, 05, 04))
            ->setExpiryDate((new \DateTime())->setDate(2016, 05, 03))
            ->setRegistration("FNZ6110")
            ->setMake((new Make())->setName("RENAULT"))
            ->setModel((new Model())->setName("CLIO"))
            ->setPrimaryColour((new Colour())->setCode("L")->setName("Grey"))
            ->setSecondaryColour((new Colour())->setCode("W")->setName("Not Stated"))
            ->setOdometerReading($odometerReading)
            ->setVehicleTestingStation($site)
            ->setVehicle($this->getMockVehicle());
    }

    private function expectedVehicleDetails()
    {
        return [
            "vrm" => "GGG455",
            "make" => "FORD",
            "model" => "MONDEO",
            "colourCode1" => "P",
            "colour1" => "Black",
            "colourCode2" => "P",
            "colour2" => "Black",
            "odometer" => 1960,
            "odometerUnit" => "M",
            "testNumber" => "196019601960",
            "testDate" => date('Y-01-01'),
            "expiryDate" => (date('Y') + 1) . '-01-01',
            "vtsNumber" => "PRE1960",
            "vtsTelNo" => "PRE1960"
        ];
    }

    public function expectedMotTestPassDetails()
    {
        return [
            "vrm" => "FNZ6110",
            "make" => "RENAULT",
            "model" => "CLIO",
            "colourCode1" => "L",
            "colour1" => "Grey",
            "colourCode2" => "W",
            "colour2" => "Not Stated",
            "odometer" => 32000,
            "odometerUnit" => "mi",
            "testNumber" => "999999999014",
            "testDate" => "2015-05-04",
            "expiryDate" => "2016-05-03",
            "vtsNumber" => "V1234",
            "vtsTelNo" => "+768-45-4433630"
        ];
    }

    private function getMockColour()
    {
        return (new Colour())
            ->setCode('P')
            ->setName('Black');
    }

    private function getDvlaMake()
    {
        return (new DvlaMake())
            ->setName('FORD');
    }

    private function getDvlaModel()
    {
        return (new DvlaModel())
            ->setName('MONDEO');
    }

}
