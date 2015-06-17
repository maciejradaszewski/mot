<?php
namespace DvsaEntitiesTest\Entity;

use DvsaCommon\Constants\PersonContactType;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonContact;
use PHPUnit_Framework_TestCase;

/**
 * Class PersonContactTest
 */
class PersonContactTest extends PHPUnit_Framework_TestCase
{
    private $person;
    private $contactDetail;
    private $personContactType;
    /** @var  PersonContact */
    private $personContact;

    public function setUp()
    {
        $this->personContactType = PersonContactType::fromId(1);
        $this->contactDetail = new ContactDetail();
        $this->person = new Person();
        $this->personContact = new PersonContact($this->contactDetail, $this->personContactType, $this->person);
    }

    public function testSettersAndGetters()
    {
        $this->assertEquals($this->contactDetail, $this->personContact->getDetails());
        $this->assertEquals($this->personContactType, $this->personContact->getType());
    }
}
