<?php

namespace Organisation\UpdateAeProperty;

use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Guid\Guid;
use DvsaCommon\Utility\TypeCheck;
use Zend\Session\Container;

/**
 * Class UpdateAeFormSessionContainer
 *
 * Stores in session all the data
 *
 * @package Organisation\UpdateAeProperty
 */
class UpdateAePropertyFormSession implements AutoWireableInterface
{
    const SESSION_CONTAINER_KEY = "UPDATE_AE_PROPERTY_FORM";

    private $container;

    public function __construct()
    {
        $this->container = new Container(self::SESSION_CONTAINER_KEY);
    }

    public function store($aeId, $propertyName, array $formData)
    {
        TypeCheck::assertArray($formData);
        unset($formData['_csrf_token']);

        $formUuid = Guid::newGuid();

        $offset = $this->toOffset($aeId, $propertyName, $formUuid);
        $this->container->offsetSet($offset, $formData);

        return $formUuid;
    }

    public function get($aeId, $propertyName, $formUuid)
    {
        $offset = $this->toOffset($aeId, $propertyName, $formUuid);

        return $this->container->offsetGet($offset);
    }

    private function toOffset($aeId, $propertyName, $formUuid)
    {
        return "$aeId/$propertyName/$formUuid";
    }
}
