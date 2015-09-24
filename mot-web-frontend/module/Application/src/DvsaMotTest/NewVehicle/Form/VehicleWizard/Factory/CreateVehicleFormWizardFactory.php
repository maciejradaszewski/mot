<?php
namespace DvsaMotTest\NewVehicle\Form\VehicleWizard\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\CreateVehicleFormWizard;
use DvsaMotTest\NewVehicle\Container\NewVehicleContainer;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\SummaryStep;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\VehicleIdentificationStep;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\VehicleSpecificationStep;
use DvsaMotTest\Service\AuthorisedClassesService;
use Zend\Session\Container;
use DvsaCommon\HttpRestJson\Client;

class CreateVehicleFormWizardFactory implements FactoryInterface
{
    /**
     * @return CreateVehicleFormWizard
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $container = new NewVehicleContainer(new Container(self::class));
        $client = $sl->get(Client::class);
        $catalogService = $sl->get('CatalogService');
        $authorisedClassesService = $sl->get(AuthorisedClassesService::class);
        $identityProvider = $sl->get('MotIdentityProvider');

        $wizard = new CreateVehicleFormWizard();

        $step1 = new VehicleIdentificationStep($container,$client,$catalogService);
        $wizard->addStep($step1);

        $step2 = new VehicleSpecificationStep($container,$client,$catalogService,$authorisedClassesService,$identityProvider);
        $step2->setPrevStep($step1);
        $wizard->addStep($step2);

        $step3 = new SummaryStep($container,$client,$catalogService,$identityProvider);
        $step3->setPrevStep($step2);
        $wizard->addStep($step3);

        return $wizard;
    }
}
