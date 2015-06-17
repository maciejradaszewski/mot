<?php

namespace DvsaCommonTest\TestUtils;

/**
 * The purpose of this class is to help in testing
 *
 * Class SampleTestObject
 *
 * @package DvsaCommonTest\TestUtils
 */
class SampleTestObject
{
    private $id;
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
