<?php

namespace DvsaMotEnforcement\Model;

use Zend\Form\Annotation;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("VehicleTestingStationSearch")
 */
class VehicleTestingStationSearch
{
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\AllowEmpty(false)
     * @Annotation\Required(true)
     * @Annotation\Filter({"name":"StringToUpper"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Search for recently tested vehicles at VTS"})
     */
    public $search;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Search"})
     */
    public $submit;

    public function exchangeArray($data)
    {
        $this->search = (!empty($data['search'])) ? $data['search'] : null;
    }
}
