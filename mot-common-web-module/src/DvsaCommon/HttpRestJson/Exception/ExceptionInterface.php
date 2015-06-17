<?php

namespace DvsaCommon\HttpRestJson\Exception;

interface ExceptionInterface
{
    public function __construct($resourcePath, $method, $postData, $code, $errors = null);

    public function getResourcePath();

    public function getMethod();

    public function getPostData();

    public function getErrors();

    public function getMessageForRequestData();
}
