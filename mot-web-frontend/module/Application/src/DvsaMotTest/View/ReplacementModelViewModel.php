<?php

namespace DvsaMotTest\View;

use DvsaCommon\Utility\ArrayUtils;

class ReplacementModelViewModel
{

    /** @var string $id */
    private $id;
    /** @var string $name */
    private $name;
    /** @var string $code */
    private $code;

    public function __construct(array $data)
    {
        $this->id = ArrayUtils::tryGet($data, 'id');
        $this->name = ArrayUtils::tryGet($data, 'name');
        $this->code = ArrayUtils::tryGet($data, 'code');
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

}
