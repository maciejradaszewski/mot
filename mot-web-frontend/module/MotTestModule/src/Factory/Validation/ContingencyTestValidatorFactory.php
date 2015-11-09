<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModule\Factory\Validation;

use Dvsa\Mot\Frontend\MotTestModule\Validation\ContingencyTestValidator;
use DvsaCommon\Validation\CommonContingencyTestValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for ContingencyTestValidation instances.
 */
class ContingencyTestValidatorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return CommonContingencyTestValidator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ContingencyTestValidator();
    }
}
