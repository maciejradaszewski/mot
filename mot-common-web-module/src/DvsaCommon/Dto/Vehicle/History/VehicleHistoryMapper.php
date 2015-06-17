<?php

namespace DvsaCommon\Dto\Vehicle\History;

use DvsaCommon\Utility\ArrayUtils;

class VehicleHistoryMapper
{
    /**
     * Convert VehicleHistoryDto to array for transferring
     *
     * @param VehicleHistoryDto $vehicleHistoryDto
     *
     * @return array
     */
    public function fromDtoToArray(VehicleHistoryDto $vehicleHistoryDto)
    {
        $data = [];

        /** @var VehicleHistoryItemDto $item */
        foreach ($vehicleHistoryDto->getIterator() as $item) {
            $itemData = [];
            $itemData['id'] = $item->getId();
            $itemData['status'] = $item->getStatus();
            $itemData['issuedDate'] = $item->getIssuedDate();
            $itemData['motTestNumber'] = $item->getMotTestNumber();
            $itemData['testType'] = $item->getTestType();
            $itemData['allowEdit'] = $item->isAllowEdit();

            $site = $item->getSite();
            $siteData = [];
            $siteData['id'] = $site->getId();
            $siteData['name'] = $site->getName();
            $siteData['address'] = $site->getAddress();

            $itemData['site'] = $siteData;

            $data[] = $itemData;
        }

        return $data;
    }

    /**
     * Convert transferred array to VehicleHistoryDto
     *
     * @param array $data
     * @param int   $siteId
     *
     * @return VehicleHistoryDto
     */
    public function fromArrayToDto(array $data, $siteId)
    {
        $vehicleHistoryDto = new VehicleHistoryDto();

        foreach ($data as $motTestData) {
            $itemDto = new VehicleHistoryItemDto();

            $itemDto->setId(ArrayUtils::get($motTestData, 'id'));
            $itemDto->setStatus(ArrayUtils::get($motTestData, 'status'));
            $itemDto->setIssuedDate(ArrayUtils::get($motTestData, 'issuedDate'));
            $itemDto->setMotTestNumber(ArrayUtils::get($motTestData, 'motTestNumber'));
            $itemDto->setTestType(ArrayUtils::get($motTestData, 'testType'));
            $itemDto->setAllowEdit(ArrayUtils::get($motTestData, 'allowEdit'));

            $siteData = ArrayUtils::get($motTestData, 'site');
            $siteDto = $itemDto->getSite();
            $siteDto->setId(ArrayUtils::get($siteData, 'id'));
            $siteDto->setName(ArrayUtils::get($siteData, 'name'));
            $siteDto->setAddress(ArrayUtils::get($siteData, 'address'));

            if ($siteId === 0 || $itemDto->getSiteId() === $siteId) {
                $vehicleHistoryDto->addItem($itemDto);
            } else {
                $vehicleHistoryDto->addItemToOtherSite($itemDto);
            }
        }

        return $vehicleHistoryDto;
    }
}
