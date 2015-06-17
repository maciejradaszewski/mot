<?php

namespace DvsaMotTest\Model;

use Zend\Form\Annotation;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("LocationSelect")
 */
class LocationSelect
{
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     */
    public $vtsId;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Confirm"})
     */
    public $submit;

    /**
     * @Annotation\Type("Zend\Form\Element\Hidden")
     */
    public $back;

    public function exchangeArray($data)
    {
    }
}
