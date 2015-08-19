<?php

namespace DvsaAuthentication\IdentityFactory;

use DvsaAuthentication\Identity;
use DvsaAuthentication\IdentityFactory;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\PersonRepository;

class DoctrineIdentityFactory implements IdentityFactory
{
    /**
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * @param PersonRepository $personRepository
     */
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @param string $username
     * @param string $token
     * @param string $uuid
     *
     * @return Identity
     */
    public function create($username, $token, $uuid)
    {
        $person = $this->personRepository->findOneBy(['username' => $username]);

        if (!$person instanceof Person) {
            throw new \InvalidArgumentException(sprintf('Person "%s" not found', $username));
        }

        return (new Identity($person))->setToken($token)->setUuid($uuid);
    }
}