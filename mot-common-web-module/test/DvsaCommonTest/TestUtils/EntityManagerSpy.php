<?php

namespace DvsaCommonTest\TestUtils;

use DvsaCommon\Utility\ArrayUtils;

class EntityManagerSpy
{
    private $removeSpy;
    private $persistSpy;

    public function __construct(\PHPUnit_Framework_MockObject_MockObject $entityManager)
    {
        $this->removeSpy= new MethodSpy($entityManager, 'remove');
        $this->persistSpy= new MethodSpy($entityManager, 'persist');
    }

    public function getPersistedObjectsByType($class)
    {
        return ArrayUtils::filter($this->getPersistedObjects(), function ($persistedObject) use ($class) {
            return $persistedObject instanceof $class;
        });
    }

    public function getPersistedObjects()
    {
        foreach ($this->persistSpy->getInvocations() as $invocation) {
            yield $invocation->parameters[0];
        }
    }

    public function wasPersisted($object)
    {
        return ArrayUtils::anyMatch($this->getPersistedObjects(), function ($persistedObject) use ($object) {

            return $object === $persistedObject;
        });
    }

    public function getRemovedObjects()
    {
        foreach ($this->removeSpy->getInvocations() as $invocation) {
            yield $invocation->parameters[0];
        }
    }

    public function wasRemoved($object)
    {
        return ArrayUtils::anyMatch($this->getRemovedObjects(), function ($persistedObject) use ($object) {
            return $object === $persistedObject;
        });
    }

    public function removedCount()
    {
        return $this->removeSpy->invocationCount();
    }

    public function persistCount()
    {
        return $this->persistSpy->invocationCount();
    }
}
