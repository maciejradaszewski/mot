<?php

namespace DvsaClient\Mapper;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\UrlBuilder\PersonUrlBuilder;

class ExpiredPasswordMapper extends Mapper
{
    public function postPasswordExpiredDate($token, \DateTime $expiryDate)
    {
        $urlBuilder = PersonUrlBuilder::passwordExpiry();
        $data = ['expiry-date' => $expiryDate->format(DateUtils::FORMAT_ISO_WITH_TIME)];

        $this->client->setAccessToken($token);

        $this->client->post($urlBuilder, $data);
    }
}
