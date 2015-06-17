<?php

namespace DvsaCommon\Dto\Mailer;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Used to communicate generic request for sending out reminder emails
 */
class MailerDto extends AbstractDataTransferObject
{
    private $data;

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}
