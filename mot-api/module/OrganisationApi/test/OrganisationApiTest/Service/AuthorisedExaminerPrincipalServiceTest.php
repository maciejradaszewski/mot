<?php
namespace OrganisationApiTest\Service;

use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Constants\PersonContactType;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Address;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonContact;
use DvsaEntities\Repository\OrganisationRepository;
use OrganisationApi\Service\AuthorisedExaminerPrincipalService;
use UserApi\Application\Service\AccountService;

/**
 * Class AuthorisedExaminerPrincipalServiceTest
 *
 * @package OrganisationApiTest\Service
 */
class AuthorisedExaminerPrincipalServiceTest extends AbstractServiceTestCase
{
    /**
     * @var AuthorisedExaminerPrincipalService
     */
    private $authorisedExaminerPrincipalService;
    private $organisationRepository;
    private $accountService;

    /**
     * @var Organisation
     */
    private $authorisedExaminer;

    public function setUp()
    {
        $this->organisationRepository = $this->getMockWithDisabledConstructor(OrganisationRepository::class);
        $this->accountService         = $this->getMockWithDisabledConstructor(AccountService::class);

        /** @var AuthorisationService $authorisationService */
        $authorisationService = XMock::of(MotAuthorisationServiceInterface::class);

        $this->authorisedExaminerPrincipalService = new AuthorisedExaminerPrincipalService(
            $this->organisationRepository,
            $this->accountService,
            $authorisationService
        );

        $this->authorisedExaminer = $this->buildAuthorisedExaminer();
    }

    public function testGetAuthorisedExaminer()
    {
        $this->organisationRepository->expects($this->any())->method('getAuthorisedExaminer')->will(
            $this->returnValue($this->authorisedExaminer)
        );

        $this->authorisedExaminerPrincipalService->getForAuthorisedExaminer(1);
    }

    public function buildAuthorisedExaminer()
    {
        $address = new Address();

        $contactDetail = new ContactDetail();
        $contactDetail->setAddress($address);

        $personalContactType = new \DvsaEntities\Entity\PersonContactType();
        $personalContactType->setName(PersonContactType::personalContact()->getName());

        $workContactType     = new \DvsaEntities\Entity\PersonContactType();
        $workContactType->setName(PersonContactType::workContact()->getName());

        $authorisedExaminer = new AuthorisationForAuthorisedExaminer();

        $aep1 = new Person();
        $aep1->setDateOfBirth(new \DateTime('NOW'));
        $personalContact = new PersonContact($contactDetail, $personalContactType, $aep1);
        $aep1->addContact($personalContact);

        $aep2 = new Person();
        $aep2->setDateOfBirth(new \DateTime('NOW'));

        $workContact = new PersonContact($contactDetail, $workContactType, $aep2);
        $aep2->addContact($workContact);

        $organisation = new Organisation();
        $organisation->setAuthorisedExaminer($authorisedExaminer);

        $authorisedExaminer->setOrganisation($organisation);
        $authorisedExaminer->addPrincipal($aep1);
        $authorisedExaminer->addPrincipal($aep2);

        return $organisation;
    }
}
