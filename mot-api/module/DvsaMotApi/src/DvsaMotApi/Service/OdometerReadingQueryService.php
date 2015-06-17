<?php

namespace DvsaMotApi\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommonApi\Authorisation\Assertion\ReadMotTestAssertion;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\OdometerReadingRepository;
use DvsaMotApi\Service\Validator\Odometer\OdometerReadingDeltaAnomalyChecker;
use Zend\Authentication\AuthenticationService;

/**
 * Retrieves information on odometer reading
 *
 * Class OdometerReadinQueryService
 *
 * @package DvsaMotApi\Service
 */
class OdometerReadingQueryService
{

    /**
     * @var OdometerReadingRepository $readingRepository
     */
    private $readingRepository;
    /**
     * @var OdometerReadingDeltaAnomalyChecker $anomalyChecker
     */
    private $anomalyChecker;
    /**
     * @var AuthorisationServiceInterface $authService
     */
    private $authService;

    /**
     * @var ReadMotTestAssertion
     */
    private $readMotTestAssertion;

    /**
     * @var MotTestRepository
     */
    private $motTestRepository;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @param OdometerReadingDeltaAnomalyChecker $odometerReadingDeltaAnomalyChecker
     * @param OdometerReadingRepository $odometerReadingRepository
     * @param AuthorisationServiceInterface $authorizationService
     * @param ReadMotTestAssertion $readMotTestAssertion
     * @param MotTestRepository $motTestRepository
     */
    public function __construct(
        OdometerReadingDeltaAnomalyChecker $odometerReadingDeltaAnomalyChecker,
        OdometerReadingRepository $odometerReadingRepository,
        AuthorisationServiceInterface $authorizationService,
        ReadMotTestAssertion $readMotTestAssertion,
        MotTestRepository $motTestRepository,
        AuthenticationService $authenticationService
    ) {
        $this->readingRepository = $odometerReadingRepository;
        $this->anomalyChecker = $odometerReadingDeltaAnomalyChecker;
        $this->authService = $authorizationService;
        $this->readMotTestAssertion = $readMotTestAssertion;
        $this->motTestRepository = $motTestRepository;
        $this->authenticationService = $authenticationService;
    }

    /**
     * Returns notices for odometer reading done in a given test.
     * Currently, notices come from delta anomaly checker only.
     *
     * @param $motTestNumber
     *
     * @return array
     *      array of strings (notices)
     * @throws UnauthorisedException
     */
    public function getNotices($motTestNumber)
    {
        if (!$this->motTestRepository->isTesterForMot(
            $this->authenticationService->getIdentity()->getUserId(),
            $motTestNumber
        )) {
            throw new UnauthorisedException('You cannot read mot test because you are not the mot test owner');
        }
        $notices = [];

        $currentReading = $this->readingRepository->findReadingForTest($motTestNumber);
        $previousReading = $this->readingRepository->findPreviousReading($motTestNumber);
        if ($currentReading && $previousReading) {
            $notices = $this->anomalyChecker->check($currentReading, $previousReading)->toArrayOfTexts();
        }
        return $notices;
    }
}
