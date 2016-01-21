<?php

namespace Site\UpdateVtsProperty;

use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Guid\Guid;
use DvsaCommon\Utility\TypeCheck;
use Zend\Session\Container;

/**
 * Class UpdateVtsFormSessionContainer
 *
 * Stores in session all the data
 *
 * @package Site\UpdateVtsProperty
 */
class UpdateVtsPropertyFormSession implements AutoWireableInterface
{
    const SESSION_CONTAINER_KEY = "UPDATE_VTS_PROPERTY_FORM";

    private $container;

    public function __construct()
    {
        $this->container = new Container(self::SESSION_CONTAINER_KEY);
    }

    public function store($vtsId, $propertyName, array $formData)
    {
        TypeCheck::assertArray($formData);
        unset($formData['_csrf_token']);

        $formUuid = Guid::newGuid();

        $offset = $this->toOffset($vtsId, $propertyName, $formUuid);
        $this->container->offsetSet($offset, $formData);

        return $formUuid;
    }

    public function get($vtsId, $propertyName, $formUuid)
    {
        $offset = $this->toOffset($vtsId, $propertyName, $formUuid);

        return $this->container->offsetGet($offset);
    }

    private function toOffset($vtsId, $propertyName, $formUuid)
    {
        return "$vtsId/$propertyName/$formUuid";
    }
}
