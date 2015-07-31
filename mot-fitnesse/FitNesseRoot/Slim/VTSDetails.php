<?php
require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class VTSDetails
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;
    private $vtsId;
    protected $searchResult;

    public function __construct($id)
    {
        $this->vtsId = $id;
    }

    protected function fetchSearchResult()
    {
        if ($this->searchResult == null) {
            $this->searchResult = TestShared::execCurlForJsonFromUrlBuilder(
                $this,
                (new UrlBuilder())
                    ->vehicleTestingStation()
                    ->routeParam('id', $this->vtsId)
            );
        }
        return $this->searchResult;
    }

    public function query()
    {
        $result = $this->fetchSearchResult();

        $queryData = [];

        if (isset($result['error'])) {

            $queryData[] = [
                ['message', $result['error']],
                ['content', $result['content']['message']],
            ];

        } elseif (isset($result['data'])) {
            /** @var \DvsaCommon\Dto\Site\VehicleTestingStationDto $dto */
            $dto = \DvsaCommon\Utility\DtoHydrator::jsonToDto($result['data']);

            $org = $dto->getOrganisation();

            $contact = $dto->getContactByType(\DvsaCommon\Enum\SiteContactTypeCode::BUSINESS);

            $positions = $dto->getPositions();

            $queryData[] = [
                ['id', $dto->getId()],
                ['name', $dto->getName()],

                ['organisation.id', $org->getId()],
                ['organisation.slots', $org->getSlotBalance()],
                ['organisation.slotsWarning', $org->getSlotWarning()],

                ['address.addressLine1', $contact->getAddress()->getAddressLine1()],
                ['address.postcode', $contact->getAddress()->getPostcode()],
                ['address.town', $contact->getAddress()->getTown()],
                ['address.country', $contact->getAddress()->getCountry()],

                ['roles', implode(',', $dto->getTestClasses())],

                ['positions.1.role', $positions[0]->getRole()->getCode()],
            ];
        }

        return $queryData;
    }

    protected function fieldExistOrValue($object, $fieldName)
    {
        if (isset($object[$fieldName])) {
            $val = $object[$fieldName];
            if (is_array($val)) {
                return print_r($val, true);
            }

            return $object[$fieldName];
        }

        return null;
    }
}
