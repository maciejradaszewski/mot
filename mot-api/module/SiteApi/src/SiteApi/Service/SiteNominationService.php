<?php

namespace SiteApi\Service;

use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use NotificationApi\Dto\Notification;
use NotificationApi\Service\NotificationService;

/**
 * Send site nomination to user
 */
class SiteNominationService
{

    /** @var NotificationService $notificationService */
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @param Person       $nominator
     * @param SiteBusinessRoleMap $nomination
     *
     * @return int notificationId
     */
    public function sendNomination(Person $nominator, SiteBusinessRoleMap $nomination)
    {
        $role = $nomination->getSiteBusinessRole()->getName();

        $data = (new Notification())
            ->setRecipient($nomination->getPerson()->getId())
            ->setTemplate(Notification::TEMPLATE_SITE_NOMINATION)
            ->addField('siteOrOrganisationId', $nomination->getSite()->getSiteNumber())
            ->addField('siteName', $nomination->getSite()->getName())
            ->addField('positionName', $role)
            ->addField('nominatorName', $nominator->getDisplayName())
            ->addField('nominatorId', $nominator->getId())
            ->addField('nominationId', $nomination->getId())
            ->addField('role', $this->getFacadeRoleName($role))
            ->addField('username', $nomination->getPerson()->getUsername())
            ->toArray();

        return $this->notificationService->add($data);
    }

    private function getFacadeRoleName($role)
    {
        $facadeRoles = [
            'Site manager' => SiteBusinessRoleCode::SITE_MANAGER,
            'Site admin'   => SiteBusinessRoleCode::SITE_ADMIN,
            'Tester'       => SiteBusinessRoleCode::TESTER,
        ];

        if (false === array_key_exists($role, $facadeRoles)) {
            throw new BadRequestException(
                'You cannot assign this role ' . $role,
                BadRequestException::ERROR_CODE_BUSINESS_FAILURE
            );
        }
        return $facadeRoles[$role];
    }
}
