<?php

namespace DvsaCommon\Validator;


use Zend\Validator\Hostname;

class HostnameValidator extends Hostname
{
    public function __construct($options = [])
    {
        parent::__construct($options);
        array_push($this->validTlds, 'test');
    }
}