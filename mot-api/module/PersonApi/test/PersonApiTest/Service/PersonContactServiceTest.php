<?php

namespace PersonApiTest\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Dto\Person\PersonContactDto;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Validator\EmailAddressValidator;
use DvsaCommonApi\Service\Exception\DataValidationException;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\PersonContact;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Repository\PersonContactRepository;
use OrganisationApi\Service\Mapper\PersonContactMapper;
use PersonApi\Service\PersonContactService;
use PersonApi\Service\Validator\PersonalDetailsValidator;
use Zend\Authentication\AuthenticationService;
use DvsaAuthentication\Identity;

class PersonContactServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|PersonContactRepository */
    private $personContactRepositoryMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|PersonContactMapper */
    private $personContactMapperMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|EntityRepository */
    private $emailRepositoryMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|PersonalDetailsValidator */
    private $personalDetailsValidatorMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|AuthenticationService */
    private $authenticationServiceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|AuthorisationService */
    private $authorisationServiceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Identity */
    private $identityMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|EntityManager */
    private $emMock;

    public function setUp()
    {
        $this->personContactRepositoryMock = XMock::of(
            PersonContactRepository::class, ['getHydratedByTypeCode', 'persist']
        );
        $this->personContactMapperMock = XMock::of(PersonContactMapper::class, ['toDto']);
        $this->emailRepositoryMock = XMock::of(EntityRepository::class);
        $this->personalDetailsValidatorMock = XMock::of(PersonalDetailsValidator::class);
        $this->authenticationServiceMock = XMock::of(AuthenticationService::class, ['getIdentity']);
        $this->authorisationServiceMock = XMock::of(AuthorisationService::class, ['assertGranted']);
        $this->identityMock = XMock::of(Identity::class, ['getUserId']);
        $this->emMock = XMock::of(EntityManager::class);
    }

    private function createService()
    {
        return new PersonContactService(
            $this->personContactRepositoryMock,
            $this->personContactMapperMock,
            $this->emailRepositoryMock,
            $this->personalDetailsValidatorMock,
            $this->authenticationServiceMock,
            $this->authorisationServiceMock,
            $this->emMock
        );
    }

    public function testGetForPersonIdWithValidIdReturnsDto()
    {
        $contactMock = XMock::of(PersonContact::class);
        $dtoMock = XMock::of(PersonContactDto::class);
        $this->personContactRepositoryMock->expects($this->once())
            ->method('getHydratedByTypeCode')
            ->willReturn($contactMock);
        $this->personContactMapperMock->expects($this->once())
            ->method('toDto')
            ->willReturn($dtoMock);
        $service = $this->createService();
        $response = $service->getForPersonId(1);
        $this->assertEquals($dtoMock, $response);
    }

    public function testGetForPersonIdWithInvalidIdThrowsException()
    {
        $this->personContactRepositoryMock->expects($this->once())
            ->method('getHydratedByTypeCode')
            ->will($this->throwException(new NotFoundException('PersonContact')));
        $this->setExpectedException(NotFoundException::class);
        $service = $this->createService();
        $service->getForPersonId(1);
    }
    public function testUpdateEmailForPersonIdReturnsDto()
    {
        $personId = 1;
        $this->authenticationServiceMock->expects($this->once())
            ->method('getIdentity')
            ->willReturn($this->identityMock);
        $this->identityMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($personId);
        $this->personalDetailsValidatorMock->expects($this->once())
            ->method('validateEmail')
            ->willReturn(true);
        $contactMock = XMock::of(PersonContact::class, ['getDetails']);
        $dtoMock = XMock::of(PersonContactDto::class);
        $contactDetailsMock = XMock::of(ContactDetail::class, ['getEmails']);
        $emailsCollectionMock = XMock::of(ArrayCollection::class, ['isEmpty', 'add']);
        $contactMock->expects($this->once())
            ->method('getDetails')
            ->willReturn($contactDetailsMock);
        $contactDetailsMock->expects($this->once())
            ->method('getEmails')
            ->willReturn($emailsCollectionMock);
        $emailsCollectionMock->expects($this->once())
            ->method('isEmpty')
            ->willReturn(true);
        $this->personContactRepositoryMock->expects($this->once())
            ->method('getHydratedByTypeCode')
            ->willReturn($contactMock);
        $this->personContactMapperMock->expects($this->once())
            ->method('toDto')
            ->willReturn($dtoMock);
        $service = $this->createService();
        $response = $service->updateEmailForPersonId($personId, ['emails' => ['personcontactservicetest@' . EmailAddressValidator::TEST_DOMAIN]]);
        $this->assertEquals($dtoMock, $response);
    }

    public function testupdateEmailForPersonIdWithNoDataThrowsException()
    {
        $personId = 1;
        $this->authenticationServiceMock->expects($this->once())
            ->method('getIdentity')
            ->willReturn($this->identityMock);
        $this->identityMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($personId);
        $this->setExpectedException(DataValidationException::class);
        $service = $this->createService();
        $service->updateEmailForPersonId($personId, []);
    }

    public function testUpdateEmailForPersonIdWithInvalidDataThrowsException()
    {
        $personId = 1;
        $this->authenticationServiceMock->expects($this->once())
            ->method('getIdentity')
            ->willReturn($this->identityMock);
        $this->identityMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($personId);
        $this->personalDetailsValidatorMock->expects($this->once())
            ->method('validateEmail')
            ->willReturn(false);
        $this->setExpectedException(DataValidationException::class);
        $service = $this->createService();
        $service->updateEmailForPersonId($personId, ['emails' => ['foo@bar']]);
    }

    public function testUpdateEmailForPersonIdWithDifferentUserIdThrowsExceptionWithNoPermission()
    {
        $loggedInPersonId = 1;
        $personId = 2;
        $this->authenticationServiceMock->expects($this->once())
            ->method('getIdentity')
            ->willReturn($this->identityMock);
        $this->identityMock->expects($this->once())
            ->method('getUserId')
            ->willReturn($loggedInPersonId);
        $this->authorisationServiceMock->expects($this->once())
            ->method('assertGranted')
            ->will($this->throwException(new UnauthorisedException('Not allowed')));
        $this->setExpectedException(UnauthorisedException::class);
        $service = $this->createService();
        $service->updateEmailForPersonId($personId, ['emails' => ['personcontactservicetest@' . EmailAddressValidator::TEST_DOMAIN]]);
    }
}
