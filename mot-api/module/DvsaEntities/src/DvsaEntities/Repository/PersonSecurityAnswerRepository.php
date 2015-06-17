<?php
namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityRepository;
use DvsaEntities\Entity\SecurityQuestion;

class PersonSecurityAnswerRepository extends EntityRepository
{
    /**
     * @return PersonSecurityAnswer[]
     */
    public function findAll()
    {
        $questions = parent::findAll();

        return $questions;
    }
}
