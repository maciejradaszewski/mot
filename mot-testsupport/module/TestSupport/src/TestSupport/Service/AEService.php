<?php

namespace TestSupport\Service;

use TestSupport\Helper\TestSupportRestClientHelper;
use TestSupport\Helper\TestDataResponseHelper;
use TestSupport\Helper\DataGeneratorHelper;
use DvsaCommon\Constants\OrganisationType;
use Doctrine\ORM\Query\ResultSetMapping;
use DvsaCommon\UrlBuilder\UrlBuilder;
use DvsaCommon\Enum\CompanyTypeName;
use DvsaCommon\Utility\ArrayUtils;
use Doctrine\ORM\EntityManager;
use Zend\View\Model\JsonModel;

class AEService
{

    /**
     * @var TestSupportRestClientHelper
     */
    private $restClientHelper;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(
        TestSupportRestClientHelper $restClientHelper,
        EntityManager $em
    ) {
        $this->restClientHelper = $restClientHelper;
        $this->em = $em;
    }

    /**
     * @param mixed $data including optional "diff" string to differentiate testers,
     *                    requestor => {username,password} of DVSA scheme management user with whom to create AE
     *                    optional "slots", to specify the number of slots (default 2000)
     *
     * @return JsonModel ID of new AE
     */
    public function create($data)
    {
        $dataGenerator = DataGeneratorHelper::buildForDifferentiator($data);
        $emailAddress = $dataGenerator->emailAddress('org-');
        $aeRef = $dataGenerator->generateAeRef();
        $aeName = ArrayUtils::tryGet($data, 'organisationName', $dataGenerator->organisationName());

        $result = $this->restClientHelper->getJsonClient($data)->post(
            UrlBuilder::of()->authorisedExaminer()->toString(),
            [
                'organisationName'                 => $aeName,
                'authorisedExaminerReference'      => $aeRef,
                'organisationType'                 => OrganisationType::AUTHORISED_EXAMINER,
                'companyType'                      => CompanyTypeName::REGISTERED_COMPANY,
                'addressLine1'                     => $dataGenerator->addressLine1(),
                'town'                             => "Ipswich",
                'postcode'                         => "IP1 1LL",
                'email'                            => ArrayUtils::tryGet($data, 'emailAddress', $emailAddress),
                'emailConfirmation'                => ArrayUtils::tryGet($data, 'emailAddress', $emailAddress),
                'phoneNumber'                      => $dataGenerator->phoneNumber(),
                'correspondenceContactDetailsSame' => true,
            ]
        );

        $aeId = $result['data']['id'];
        $this->addSlotsToAe($aeId, ArrayUtils::tryGet($data, 'slots', 2000));

        return TestDataResponseHelper::jsonOk(["message" => "Authorised Examiner created",
            "id" => $aeId,
            "aeRef" => $aeRef,
            "aeName" => $aeName,
        ]);
    }
    /**
     * @param int $aeId
     * @param int $slots
     */
    private function addSlotsToAe($aeId, $slots)
    {
        $this->em->getConnection()->executeUpdate(
            "UPDATE organisation SET slots_balance = :slots WHERE id = :id",
            ["slots" => $slots, "id" => $aeId]
        );

        $this->em->flush();
    }

}
