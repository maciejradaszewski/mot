<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Person\PersonDto;

/**
 * Class CommentDto
 *
 * @package DvsaCommon\Dto\Common
 */
class CommentDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  string */
    private $comment;
    /** @var  PersonDto */
    private $author;

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

    /**
     * @return PersonDto
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param PersonDto $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }
}
