<?php

namespace DvsaEntities\Repository;

use DvsaEntities\Entity\Notification;
use DvsaCommonApi\Service\Exception\NotFoundException;

class NotificationRepository extends AbstractMutableRepository
{
    /**
     * @param int $id
     * @return Notification
     * @throws NotFoundException
     */
    public function get($id)
    {
        $result = $this->find($id);
        if (is_null($result)) {
            throw new NotFoundException("Notification", $id);
        }

        return $result;
    }

    /**
     * @param int $personId
     * @param int $templateId
     * @return Notification[]
     */
    public function findAllByTemplateId($personId, $templateId)
    {
        return $this
            ->createQueryBuilder("n")
            ->addSelect(["nt", "f"])
            ->innerJoin("n.notificationTemplate", "nt")
            ->leftjoin("n.fields", "f")
            ->where("n.recipient = :personId")
            ->andWhere("nt.id = :templateId")
            ->setParameter("personId", $personId)
            ->setParameter("templateId", $templateId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $personId
     * @return Notification[]
     */
    public function findAllByPersonId($personId)
    {
        return $this
            ->createQueryBuilder("n")
            ->where("n.recipient = :personId")
            ->orderBy("n.readOn", "ASC")
            ->addOrderBy("n.createdOn", "DESC")
            ->addOrderBy("n.id", "DESC")
            ->setParameter("personId", $personId)
            ->getQuery()
            ->getResult();
    }
}
