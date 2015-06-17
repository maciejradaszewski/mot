<?php

namespace DvsaEntities\Repository;

use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\MessageType;

class MessageTypeRepository extends AbstractMutableRepository
{
    /**
     * @param string $code
     *
     * @return MessageType
     *
     * @throws NotFoundException
     */
    public function getByCode($code)
    {
        $messageType = $this->findOneBy(['code' => $code]);

        if ($messageType === null) {
            throw new NotFoundException('MessageType', $code);
        }

        return $messageType;
    }
}
