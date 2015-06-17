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

        } elseif (isset($result['data']['vehicleTestingStation'])) {
            $resultData = $result['data']['vehicleTestingStation'];

            $org = $resultData['organisation'];

            $address = $resultData['address'];
            $positions = $resultData['positions'];

            $queryData[] = [
                ['id', $resultData['id']],
                ['name', $resultData['name']],

                ['organisation.id', $org['id']],
                ['organisation.slots', $org['slotBalance']],
                ['organisation.slotsWarning', $org['slotsWarning']],

                ['address.id', $address['id']],
                ['address.addressLine1', $address['addressLine1']],
                ['address.postcode', $address['postcode']],
                ['address.town', $address['town']],
                ['address.country', $address['country']],
                ['address.createdOn', $this->fieldExistOrValue($address, 'createdOn')],
                ['address.lastUpdateOn', $this->fieldExistOrValue($address, 'lastUpdatedOn')],
                ['address.version', $address['version']],
                ['address.createdBy', $this->fieldExistOrValue($address, 'createdBy')],
                ['address.lastUpdatedBy', $this->fieldExistOrValue($address, 'lastUpdatedBy')],

                ['roles', json_encode($resultData['roles'])],

                ['positions.1.role', $positions[0]['role']],
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
