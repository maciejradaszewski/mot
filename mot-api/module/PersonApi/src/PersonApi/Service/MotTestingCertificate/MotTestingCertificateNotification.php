<?php
namespace PersonApi\Service\MotTestingCertificate;

use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaEntities\Entity\QualificationAward;
use NotificationApi\Dto\Notification;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use NotificationApi\Service\NotificationService;

class MotTestingCertificateNotification implements AutoWireableInterface
{
    private $motIdentityProvider;
    private $notificationService;

    public function __construct(
        MotIdentityProviderInterface $motIdentityProvider,
        NotificationService $notificationService
    ) {
        $this->motIdentityProvider = $motIdentityProvider;
        $this->notificationService = $notificationService;
    }

    /**
     * @param QualificationAward $motTestingCertificate
     * @return int
     */
    public function send(QualificationAward $motTestingCertificate)
    {
        $data = (new Notification())
            ->setRecipient($motTestingCertificate->getPerson()->getId())
            ->setTemplate(Notification::TEMPLATE_MOT_TESTING_CERTIFICATE_REMOVAL)
            ->addField("group", $motTestingCertificate->getVehicleClassGroup()->getCode())
            ->addField("user", $this->motIdentityProvider->getIdentity()->getUsername())
            ->addField("certificateNumber", $motTestingCertificate->getCertificateNumber())
            ->addField("dateOfQualification", DateTimeDisplayFormat::date($motTestingCertificate->getDateOfQualification()))
            ->toArray();

        return $this->notificationService->add($data);

    }
}
