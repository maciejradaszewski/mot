<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaMotApi\Validation;

use DvsaCommon\Validation\CommonContingencyTestValidator;
use DvsaEntities\Entity\EmergencyLog;
use DvsaMotApi\Service\EmergencyService;
use Exception;
use SiteApi\Service\SiteService;
use Zend\Validator\Callback;

/**
 * ContingencyTest Validator.
 */
class ContingencyTestValidator extends CommonContingencyTestValidator
{
    /**
     * @var EmergencyService
     */
    private $emergencyService;

    /**
     * @var SiteService
     */
    private $siteService;

    /**
     * ContingencyTestValidator constructor.
     *
     * @param EmergencyService $emergencyService
     * @param SiteService      $siteService
     */
    public function __construct(EmergencyService $emergencyService, SiteService $siteService)
    {
        $this->emergencyService = $emergencyService;
        $this->siteService = $siteService;

        parent::__construct();
    }

    /**
     * @var EmergencyLog|null The last successfully retrieved EmergencyLog entity.
     */
    private $emergencyLog;

    /**
     * Get the last validated emergencyLog entity.
     *
     * @return EmergencyLog The emergency log
     */
    public function getEmergencyLog()
    {
        return $this->emergencyLog;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSiteValidator()
    {
        $validatorChain = parent::getSiteValidator();

        /*
         * "must be a valid site"
         */
        $siteExists = new Callback(function ($data) {
            try {
                $this->siteService->getSite($data['siteId']);

                return true;
            } catch (Exception $e) {
                return false;
            }
        });
        $siteExists->setMessage('must be a valid site');

        $validatorChain->attach($siteExists, true);

        return $validatorChain;
    }

    /**
     * {@inheritdoc}
     */
    protected function getContingencyCodeValidator()
    {
        $validatorChain = parent::getContingencyCodeValidator();

        /*
         * "must be a valid contingency code"
         */
        $validCode = new Callback(function ($data) {
            $this->emergencyLog = $this->emergencyService->getEmergencyLog($data['contingencyCode']);

            return $this->emergencyLog instanceof EmergencyLog;
        });
        $validCode->setMessage('must be a valid contingency code');

        $validatorChain->attach($validCode, true);

        return $validatorChain;
    }
}
