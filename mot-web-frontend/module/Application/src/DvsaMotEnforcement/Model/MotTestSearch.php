<?php

namespace DvsaMotEnforcement\Model;

use Zend\Form\Annotation;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("VehicleTestSearch")
 */
class MotTestSearch
{
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\AllowEmpty(false)
     * @Annotation\Required(true)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Search..."})
     */
    public $searchValue;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Search"})
     */
    public $submit;

    public function exchangeArray($data)
    {
        $this->searchValue = (!empty($data['searchValue'])) ? $data['searchValue'] : null;
    }
}
