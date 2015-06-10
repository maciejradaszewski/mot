<?php

namespace DvsaClient\Mapper;

use DvsaCommon\UrlBuilder\PersonUrlBuilder;
use DvsaCommon\UrlBuilder\UrlBuilder;

/**
 * Class TesterQualificationStatusMapper
 *
 * @package DvsaClient\Mapper
 */
class TesterQualificationStatusMapper extends Mapper
{
    public function getTesterQualificationStatus($testerId)
    {
        $url = PersonUrlBuilder::motTesting($testerId)->toString();
        return $this->client->get($url);
    }
}
