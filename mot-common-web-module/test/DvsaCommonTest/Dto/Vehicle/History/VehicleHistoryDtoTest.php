<?php

namespace DvsaCommonTest\Dto\Vehicle\History;

use DvsaCommon\Dto\Vehicle\History\VehicleHistoryDto;
use DvsaCommon\Dto\Vehicle\History\VehicleHistoryItemDto;
use DvsaCommon\Dto\Vehicle\History\VehicleHistoryItemSiteDto;
use DvsaCommon\Enum\MotTestStatusName;

class VehicleHistoryDtoTest extends \PHPUnit_Framework_TestCase
{
    protected $dto;

    public function setUp()
    {
        $this->dto = new VehicleHistoryDto;
        $item1 = $this->getItem(1, MotTestStatusName::PASSED, 1000000001, 'normal', new \DateTime('2012-07-18 11:14:15'), $this->getItemSite(1, '1 filton', 'filton1'));
        $item2 = $this->getItem(2, MotTestStatusName::FAILED, 1000000002, 'normal', new \DateTime('2012-07-20 11:14:15'), $this->getItemSite(2, '2 filton', 'filton2'));
        $item3 = $this->getItem(3, MotTestStatusName::PASSED, 1000000003, 'normal', new \DateTime('2012-07-19 11:14:15'), $this->getItemSite(3, '3 filton', 'filton3'));
        $item4 = $this->getItem(4, MotTestStatusName::ABANDONED, 1000000004, 'normal', new \DateTime('2012-07-21 11:14:15'), $this->getItemSite(4, '4 filton', 'filton4'));

        $this->dto->addItem($item1);
        $this->dto->addItem($item2);
        $this->dto->addItem($item3);
        $this->dto->addItem($item4);
    }

    public function testIsMostRecent()
    {
        $item1 = $this->dto->getIterator()[0];
        $item2 = $this->dto->getIterator()[1];
        $item3 = $this->dto->getIterator()[2];
        $item4 = $this->dto->getIterator()[3];

        $this->assertFalse($this->dto->isMostRecentNonAbandoned($item1));
        $this->assertTrue($this->dto->isMostRecentNonAbandoned($item2));
        $this->assertFalse($this->dto->isMostRecentNonAbandoned($item3));
        $this->assertFalse($this->dto->isMostRecentNonAbandoned($item4));
    }

    /**
     * @param $id integer
     * @param $motNumber integer
     * @param $testType string
     * @param VehicleHistoryItemSiteDto $site
     * @return VehicleHistoryItemDto
     */
    private function getItem($id, $status = VehicleHistoryItemDto::DISPLAY_PASS_STATUS_VALUE, $motNumber, $testType, \DateTime $issuedDate, VehicleHistoryItemSiteDto $site)
    {
        $item = new VehicleHistoryItemDto;
        $item->setId($id);
        $item->setStatus($status);
        $item->setMotTestNumber($motNumber);
        $item->setTestType($testType);
        $item->setIssuedDate($issuedDate->format(\DateTime::ISO8601));
        $item->setSite($site);
        return $item;
    }

    /**
     * @param $id integer
     * @param $motNumber integer
     * @param $testType string
     * @param VehicleHistoryItemSiteDto $site
     * @return VehicleHistoryItemDto
     */
    private function getItemSite($id, $name, $address)
    {
        $item = new VehicleHistoryItemSiteDto;
        $item->setId($id);
        $item->setAddress($address);
        $item->setName($name);
        return $item;
    }
}
