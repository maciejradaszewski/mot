<?php

require_once __DIR__ . '/../configure_autoload.php';

use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\SlotPurchaseUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class SlotPurchase_RedirectionData
 *
 * Get data from CPMS required to redirect to the payment gateway
 */
class SlotPurchase_RedirectionData
{

    private $apiResult;
    public $password = TestShared::PASSWORD;
    private $amount;
    private $customerReference;
    private $totalAmount;
    private $scope;
    private $productReference;
    private $salesReference;
    private $customerName;
    private $userId;
    private $costCentre;
    private $aedm;
    private $redirectUrl;

    public function execute()
    {
        $data = [
            'scope'   => $this->scope,
            'payload' => [
                'customer_reference'  => $this->customerReference,
                'total_amount'        => $this->totalAmount,
                'scope'               => $this->scope,
                'user_id'             => $this->userId,
                'customer_name'       => $this->customerName,
                'disable_redirection' => true,
                'redirect_uri'        => $this->redirectUrl,
                'cost_centre'         => $this->costCentre,
                'payment_data'        => [
                    [
                        'product_reference' => $this->productReference,
                        'sales_reference'   => $this->salesReference,
                        'amount'            => $this->amount
                    ]
                ]
            ],
        ];

        $credentials     = new CredentialsProvider($this->aedm, TestShared::PASSWORD);
        $endPoint        = SlotPurchaseUrlBuilder::of()->redirection();
        $this->apiResult = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $credentials,
            $endPoint,
            $data
        );
    }

    /**
     * @param mixed $aedm
     */
    public function setAedm($aedm)
    {
        $this->aedm = $aedm;
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @param mixed $costCentre
     */
    public function setCostCentre($costCentre)
    {
        $this->costCentre = $costCentre;
    }

    /**
     * @param mixed $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * @param mixed $customerReference
     */
    public function setCustomerReference($customerReference)
    {
        $this->customerReference = $customerReference;
    }

    /**
     * @param mixed $totalAmount
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @param mixed $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @param mixed $productReference
     */
    public function setProductReference($productReference)
    {
        $this->productReference = $productReference;
    }

    /**
     * @param mixed $salesReference
     */
    public function setSalesReference($salesReference)
    {
        $this->salesReference = $salesReference;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function gatewayUrl()
    {
        if (isset($this->apiResult['data']['gateway_url']) and !empty($this->apiResult['data']['gateway_url'])) {
            return true;
        }

        return 0;
    }

    public function receiptReference()
    {
        if (isset($this->apiResult['data']['receipt_reference'])
            and !empty($this->apiResult['data']['receipt_reference'])
        ) {
            return true;
        }

        return 0;
    }

    public function errorCode()
    {
        if (isset($this->apiResult['data']['code'])) {
            return $this->apiResult['data']['code'];
        }

        return 0;
    }
}
