<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Service;

use Doctrine\ORM\EntityRepository;
use Dvsa\Mot\Api\RegistrationModule\Controller\RegistrationController;

/**
 * Class DuplicatedEmailChecker
 */
class DuplicatedEmailChecker
{
    private $entityRepository;

    public function __construct(EntityRepository $emailRepository)
    {
        $this->entityRepository = $emailRepository;
    }

    public function isEmailDuplicated($hashedEmail)
    {
        $result = $this->entityRepository->findBy([RegistrationController::KEY_EMAIL => $hashedEmail, 'isPrimary' => 1]);
        return !empty($result);
    }
}
