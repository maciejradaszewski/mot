<?php

namespace Core\ViewModel\Sidebar;

class GeneralSidebarStatusItem
{
    private $key;
    private $value;
    private $modifier;

    function __construct($key, $value, $modifier)
    {
        $this->key = $key;
        $this->value = $value;
        $this->modifier = $modifier;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getModifier()
    {
        return $this->modifier;
    }
}
