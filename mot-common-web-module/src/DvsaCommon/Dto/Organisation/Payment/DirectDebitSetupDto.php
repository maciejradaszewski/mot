<?php

namespace DvsaCommon\Dto\Organisation\Payment;

/**
 * Direct debit data.
 */
class DirectDebitSetupDto
{
    /**
     * @var CpmsCallParametersDto
     */
    private $cpmsCallData;

    /**
     * @var DirectDebitDto
     */
    private $directDebit;

    /**
     * @param CpmsCallParametersDto $cpmsCallData
     *
     * @return $this
     */
    public function setCpmsCallData(CpmsCallParametersDto $cpmsCallData)
    {
        $this->cpmsCallData = $cpmsCallData;
        return $this;
    }

    /**
     * @return CpmsCallParametersDto
     */
    public function getCpmsCallData()
    {
        return $this->cpmsCallData;
    }

    /**
     * @param DirectDebitDto $directDebit
     * @return $this
     */
    public function setDirectDebit(DirectDebitDto $directDebit)
    {
        $this->directDebit = $directDebit;
        return $this;
    }

    /**
     * @return \DvsaCommon\Dto\Organisation\Payment\DirectDebitDto
     */
    public function getDirectDebit()
    {
        return $this->directDebit;
    }
}
