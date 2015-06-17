<?php

namespace DvsaMotEnforcement\Model;

use Zend\Form\Annotation;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("VehicleTestingStationFullSearch")
 */
class VehicleTestingStationFullSearch
{
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\AllowEmpty(false)
     * @Annotation\Required(true)
     * @Annotation\Filter({"name":"StringToUpper"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Search for Site information..."})
     * @Annotation\Validator({"name": "StringLength", "options": {"min":2, "max":30}})
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
