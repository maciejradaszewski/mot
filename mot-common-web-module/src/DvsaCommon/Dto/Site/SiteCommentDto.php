<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class SiteCommentDto
 *
 * @package DvsaCommon\Dto\Site
 */
class SiteCommentDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  string */
    private $comment;

    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }
}
