<?php

namespace DvsaMotTest\NewVehicle\Container;

use Zend\Form\Form;
use Zend\Session\Container;
use Zend\Stdlib\ArrayObject;

class NewVehicleContainer
{
    private $cont;

    public function __construct(ArrayObject $containerImpl)
    {
        $this->cont = $containerImpl;
    }

    public function set($key, array $data)
    {
        $this->cont->offsetSet($key, $data);
        return $this;
    }

    /**
     * @param string $key
     * @return array
     */
    public function get($key)
    {
        if ($this->cont->offsetExists($key)) {
            return $this->cont->offsetGet($key);
        }

        return [];
    }

    /**
     * @param string $key
     */
    public function clear($key)
    {
        $this->clearOffset($key);
    }

    private function clearOffset($offset)
    {
        if ($this->cont->offsetExists($offset)) {
            $this->cont->offsetUnset($offset);
        }
    }
}
