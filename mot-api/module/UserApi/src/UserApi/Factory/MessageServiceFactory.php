<?php

namespace UserApi\Factory;

use AccountApi\Service\OpenAmIdentityService;
use Doctrine\ORM\EntityManager;
use DvsaCommon\Date\DateTimeHolder;
use DvsaEntities\Entity\Message;
use DvsaEntities\Entity\MessageType;
use DvsaEntities\Entity\Person;
use UserApi\Message\Service\MessageService;
use UserApi\Message\Service\Validator\MessageValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MessageServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $em */
        $em = $serviceLocator->get(EntityManager::class);

        return new MessageService(
            $em->getRepository(Message::class),
            $em->getRepository(MessageType::class),
            $em->getRepository(Person::class),
            $serviceLocator->get('DvsaAuthorisationService'),
            new MessageValidator(),
            new DateTimeHolder(),
            $serviceLocator->get(OpenAmIdentityService::class)
        );
    }
}
