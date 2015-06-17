<?php

namespace DvsaMotTest\View;

use DvsaCommon\Utility\ArrayUtils;

class ReplacementMakeViewModel
{
    private $id;
    private $code;
    private $name;

    public function __construct(array $data)
    {
        $this->id = ArrayUtils::tryGet($data, 'id');
        $this->code = ArrayUtils::tryGet($data, 'code');
        $this->name = ArrayUtils::tryGet($data, 'name');
    }

    /**
     * @param $id
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
