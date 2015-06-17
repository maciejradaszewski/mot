<?php

namespace OrganisationApi\Service;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\AuthorisedExaminerPrincipalAssociation;
use DvsaEntities\Repository\OrganisationRepository;
use OrganisationApi\Service\Mapper\AuthorisedExaminerPrincipalMapper;
use UserApi\Person\Service\BasePersonService;

/**
 * AEP service for getting, adding and deleting in context of an AE
 */
class AuthorisedExaminerPrincipalService
{

    const NO_ASSOCIATION_FOUND
        = 'Could not delete Authorised Examiner Principal that is not associated with this Authorised Examiner';

    private $organisationRepository;
    private $mapper;
    private $basePersonService;
    private $authorisationService;

    public function __construct(
        OrganisationRepository $organisationRepository,
        BasePersonService $basePersonService,
        MotAuthorisationServiceInterface $authorisationService
    ) {
        $this->organisationRepository = $organisationRepository;
        $this->mapper                 = new AuthorisedExaminerPrincipalMapper();
        $this->basePersonService      = $basePersonService;
        $this->authorisationService   = $authorisationService;
    }

    public function getForAuthorisedExaminer($authorisedExaminerId)
    {
        $permission = PermissionAtOrganisation::LIST_AEP_AT_AUTHORISED_EXAMINER;
        $this->authorisationService->assertGrantedAtOrganisation($permission, $authorisedExaminerId);

        $authorisedExaminer = $this->organisationRepository->getAuthorisedExaminer($authorisedExaminerId);

        $authorisedExaminerAuthorisation = $authorisedExaminer->getAuthorisedExaminer();
        $data                            = $this->mapper->manyToDto($authorisedExaminerAuthorisation->getAuthorisedExaminerPrincipals());

        return $data;
    }

    public function deletePrincipalForAuthorisedExaminer($authorisedExaminerId, $authorisedExaminerPrincipalId)
    {
        $permission = PermissionAtOrganisation::AUTHORISED_EXAMINER_PRINCIPAL_REMOVE;
        $this->authorisationService->assertGrantedAtOrganisation($permission, $authorisedExaminerId);

        $authorisedExaminer = $this->organisationRepository->getAuthorisedExaminer($authorisedExaminerId);

        $authorisedExaminerAuthorisation = $authorisedExaminer->getAuthorisedExaminer();

        $association = $this->getAuthorisedExaminerPrincipalAssociation(
            $authorisedExaminerPrincipalId, $authorisedExaminerAuthorisation
        );

        $this->organisationRepository->remove($association);
        $this->organisationRepository->flush($association);
    }

    public function createForAuthorisedExaminer($authorisedExaminerId, $data)
    {
        $permission = PermissionAtOrganisation::AUTHORISED_EXAMINER_PRINCIPAL_CREATE;
        $this->authorisationService->assertGrantedAtOrganisation($permission, $authorisedExaminerId);

        $authorisedExaminer              = $this->organisationRepository->getAuthorisedExaminer($authorisedExaminerId);
        $authorisedExaminerAuthorisation = $authorisedExaminer->getAuthorisedExaminer();
        $principal                       = $this->createPrincipal($data);

        $association = $authorisedExaminerAuthorisation->addPrincipal($principal);
        $this->organisationRepository->save($association);

        return ['authorisedExaminerPrincipalId' => $principal->getId()];
    }

    /**
     * @param                                    $authorisedExaminerPrincipalId
     * @param AuthorisationForAuthorisedExaminer $authorisedExaminerAuthorisation
     *
     * @return AuthorisedExaminerPrincipalAssociation
     * @throws \DvsaCommonApi\Service\Exception\BadRequestException
     */
    private function getAuthorisedExaminerPrincipalAssociation(
        $authorisedExaminerPrincipalId,
        AuthorisationForAuthorisedExaminer $authorisedExaminerAuthorisation
    ) {
        $predicate = function (AuthorisedExaminerPrincipalAssociation $association) use (
            $authorisedExaminerPrincipalId
        ) {
            return intval($authorisedExaminerPrincipalId) === $association->getPerson()->getId();
        };

        $association = ArrayUtils::firstOrNull(
            $authorisedExaminerAuthorisation->getAuthorisedExaminerPrincipalAssociation(),
            $predicate
        );

        if (!$association) {
            throw new BadRequestException(
                self::NO_ASSOCIATION_FOUND,
                BadRequestException::ERROR_CODE_INVALID_DATA
            );
        }

        return $association;
    }

    private function createPrincipal($data)
    {
        $person = $this->basePersonService->create($data);

        return $person;
    }
}
