<?php

namespace OrganisationApi\Service;

use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use NotificationApi\Dto\Notification;
use NotificationApi\Service\NotificationService;

/**
 * Send organisation nomination to user
 */
class OrganisationNominationService
{

    /** @var NotificationService $notificationService */
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @param Person                      $nominator
     * @param OrganisationBusinessRoleMap $nomination
     *
     * @return int notificationId
     */
    public function sendNomination(Person $nominator, OrganisationBusinessRoleMap $nomination)
    {
        $data = (new Notification())
            ->setRecipient($nomination->getPerson()->getId())
            ->setTemplate(Notification::TEMPLATE_ORGANISATION_NOMINATION)
            ->addField('siteOrOrganisationId', $nomination->getOrganisation()->getAuthorisedExaminer()->getNumber())
            ->addField('organisationName', $nomination->getOrganisation()->getName())
            ->addField('positionName', $nomination->getOrganisationBusinessRole()->getFullName())
            ->addField('nominatorName', $nominator->getDisplayName())
            ->addField('nominatorId', $nominator->getId())
            ->addField('nominationId', $nomination->getId())
            ->addField('role', OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DELEGATE)
            ->addField('username', $nomination->getPerson()->getUsername())
            ->toArray();

        return $this->notificationService->add($data);
    }

    public function sendNotification(Person $nominator, OrganisationBusinessRoleMap $nomination)
    {
        $data = (new Notification())
            ->setRecipient($nomination->getPerson()->getId())
            ->setTemplate(Notification::TEMPLATE_ORGANISATION_NOMINATION_GIVEN)
            ->addField('organisationName', $nomination->getOrganisation()->getName())
            ->addField('positionName', $nomination->getOrganisationBusinessRole()->getFullName())
            ->toArray();

        return $this->notificationService->add($data);
    }
}
