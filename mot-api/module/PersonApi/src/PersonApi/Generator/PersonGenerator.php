<?php

namespace PersonApi\Generator;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Person\PersonDto;

class PersonGenerator
{
    public function getPerson(PersonDto $personDto)
    {
        $retData = [
            'id'         => $personDto->getId(),
            'userName'   => $personDto->getUsername(),
            'firstName'  => $personDto->getFirstName(),
            'middleName' => $personDto->getMiddleName(),
            'familyName' => $personDto->getFamilyName(),
            'gender'     => $personDto->getGender(),
            'title'      => $personDto->getTitle(),
            '_clazz'     => 'Person',
        ];

        // AEP hasn't got date of birth
        if ($personDto->getDateOfBirth()) {
            $retData['dateOfBirth'] = $personDto->getDateOfBirth();
        }

        return ['data' => $retData];
    }
}
