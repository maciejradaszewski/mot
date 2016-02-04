<?php

namespace Organisation\UpdateAeProperty;

use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Utility\ArrayUtils;

class UpdateAePropertyProcessBuilder
{
    /**
     * @var UpdateAePropertyProcessInterface[]
     */
    private $processes = [];

    private $organisationMapper;

    public function __construct()
    {
    }

    public function add(UpdateAePropertyProcessInterface $process)
    {
        $this->processes[$process->getPropertyName()] = $process;
    }

    public function get($propertyName)
    {
        /** @var UpdateAePropertyProcessInterface $process */
        $process = ArrayUtils::tryGet($this->processes, $propertyName);

        if ($process === null) {
            throw new \InvalidArgumentException("Process for property '$propertyName' does not exist.");
        }

        return $process;
    }
}
