<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\SiteTestingDailySchedule;

/**
 * Class SiteTestingDailyScheduleRepository
 *
 * @package DvsaEntities\Repository
 * @codeCoverageIgnore
 */
class SiteTestingDailyScheduleRepository extends AbstractMutableRepository
{

    /**
     * @param EntityManager $em
     * @param ClassMetadata $class
     */
    public function __construct($em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }

    /**
     * @param int $id
     *
     * @return null|object
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function get($id)
    {
        $schedules = $this->find($id);

        if (null === $schedules) {
            throw new NotFoundException("Schedules not found");
        }

        return $schedules;
    }

    /**
     * @param $schedules SiteTestingDailySchedule[]
     */
    public function save($schedules)
    {
        parent::save($schedules);
    }
}
