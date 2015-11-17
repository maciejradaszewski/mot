<?php

namespace DvsaCommon\Utility;

class Lazy
{
    private $value;
    private $initialised = false;
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function value()
    {
        if (!$this->initialised) {
            $callback = $this->callback;
            $this->value = $callback();
            $this->initialised = true;
        }

        return $this->value;
    }
}
