<?php

namespace Site\UpdateVtsProperty;

use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Utility\ArrayUtils;

class UpdateVtsPropertyProcessBuilder
{
    /**
     * @var UpdateVtsPropertyProcessInterface[]
     */
    private $processes = [];

    private $siteMapper;

    public function __construct(SiteMapper $siteMapper)
    {
        $this->siteMapper = $siteMapper;
    }

    public function add(UpdateVtsPropertyProcessInterface $process)
    {
        $this->processes[$process->getPropertyName()] = $process;
    }

    public function get($propertyName)
    {
        /** @var UpdateVtsPropertyProcessInterface $process */
        $process = ArrayUtils::tryGet($this->processes, $propertyName);

        if ($process === null) {
            throw new \InvalidArgumentException("Process for property '$propertyName' does not exist.");
        }

        return $process;
    }
}
