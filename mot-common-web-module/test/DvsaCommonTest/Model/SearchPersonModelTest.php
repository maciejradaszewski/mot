<?php

namespace DvsaCommonTest\Model;

use DvsaCommon\Model\SearchPersonModel;

/**
 * Unit tests for SearchPersonDto
 */
class SearchPersonModelTest extends \PHPUnit_Framework_TestCase
{
    public function testGetterSetter()
    {
        $username = 'zdzisiu';
        $firstName = 'Zdzislaw';
        $lastName = 'Kowalski';
        $dateOfBirth = '1980-10-10';
        $town = 'Stoke Gifford';
        $postcode = 'CM1 2TQ';
        $email = 'dummy@example.com';

        $model = new SearchPersonModel($username, $firstName, $lastName, $dateOfBirth, $town, $postcode, $email);
        $this->assertEquals($username, $model->getUsername());
        $this->assertEquals($firstName, $model->getFirstName());
        $this->assertEquals($lastName, $model->getLastName());
        $this->assertEquals($email, $model->getEmail());
        $this->assertEquals($town, $model->getTown());
        $this->assertEquals($dateOfBirth, $model->getDateOfBirth());
        $this->assertEquals($postcode, $model->getPostcode());
    }
}
