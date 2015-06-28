<?php

namespace Dvsa\Mot\Behat\Support\Api;

class DirectDebit extends MotApi
{
    const DIRECT_DEBIT_MANDATE_SETUP    = '/slots/direct-debit';
    const DIRECT_DEBIT_MANDATE          = '/slots/direct-debit/%d';
    const DIRECT_DEBIT_MANDATE_COMPLETE = '/slots/direct-debit/%s/complete';

    public function setUpDirectDebitMandate($token, $orgId, $slots, $collectionDay, $amount)
    {

        $params = [
            'organisation'   => $orgId,
            'amount'         => $amount,
            'redirect_uri'   => 'http://mot-web-frontend.mot.gov.uk',
            'slots'          => $slots,
            'collection_day' => $collectionDay,
        ];

        return $this->sendRequest($token, 'POST', self::DIRECT_DEBIT_MANDATE_SETUP, $params);
    }

    public function completeMandateSetup($token, $orgId, $reference)
    {
        $path  = sprintf(self::DIRECT_DEBIT_MANDATE_COMPLETE, $orgId);
        $param = [
            'mandate_reference' => $reference
        ];

        return $this->sendRequest($token, 'PUT', $path, $param);
    }

    public function getActiveMandate($token, $orgId)
    {
        $path = sprintf(self::DIRECT_DEBIT_MANDATE, $orgId);

        return $this->sendRequest($token, 'GET', $path);
    }

    public function cancelDirectDebit($token, $orgId, $mandateReference)
    {
        $path = sprintf(self::DIRECT_DEBIT_MANDATE, $orgId);

        return $this->sendRequest($token, 'DELETE', $path);
    }
}
