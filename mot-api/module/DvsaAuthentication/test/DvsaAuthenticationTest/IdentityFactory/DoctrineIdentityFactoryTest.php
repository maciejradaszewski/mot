<?php

namespace DvsaAuthenticationTest\IdentityFactory;

use DvsaAuthentication\Identity;
use DvsaAuthentication\IdentityFactory;
use DvsaAuthentication\IdentityFactory\DoctrineIdentityFactory;
use DvsaCommon\Enum\PersonAuthType;
use DvsaEntities\Entity\AuthenticationMethod;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\PersonRepository;

class DoctrineIdentityFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IdentityFactory
     */
    private $identityFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $personRepository;

    public function setUp()
    {
        $this->personRepository = $this->getMockBuilder(PersonRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->identityFactory = new DoctrineIdentityFactory($this->personRepository);
    }

    public function testItIsAnIdentityFactory()
    {
        $this->assertInstanceOf(IdentityFactory::class, $this->identityFactory);
    }

    public function testItReturnsAnIdentity()
    {
        $person = $this->getPerson('tester1');
        $this->personRepository->expects($this->any())
            ->method('findIdentity')
            ->with('tester1')
            ->willReturn($person);

        $identity = $this->identityFactory->create('tester1', 'abcd', '1234', null);

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertSame($person, $identity->getPerson());
        $this->assertSame('abcd', $identity->getToken());
        $this->assertSame('1234', $identity->getUuid());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Person "tester1" not found
     */
    public function testItThrowsAnExceptionIfPersonCannotBeFound()
    {
        $this->personRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn(null);

        $this->identityFactory->create('tester1', 'abcd', '1234', null);
    }

    /**
     * @param string $username
     *
     * @return Person
     */
    private function getPerson($username)
    {
        $person = new Person();
        $person->setUsername($username);

        return $person;
    }
}