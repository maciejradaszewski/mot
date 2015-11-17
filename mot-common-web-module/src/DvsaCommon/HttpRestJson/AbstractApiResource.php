<?php

namespace DvsaCommon\HttpRestJson;
use DvsaCommon\DtoSerialization\DtoReflectiveDeserializer;

class AbstractApiResource
{
    private $httpClient;
    private $deserializer;

    public function __construct(Client $httpClient, DtoReflectiveDeserializer $deserializer)
    {
        $this->httpClient = $httpClient;
        $this->deserializer = $deserializer;
    }

    protected function getSingle($returnedDtoClass, $url)
    {
        $data = $this->httpClient->get($url)['data'];

        return $this->deserializer->deserialize($data, $returnedDtoClass);
    }

    protected function getMany($returnedDtoClass, $url)
    {
        $response = $this->httpClient->get($url);

        return $this->deserializer->deserializeArray($response['data'], $returnedDtoClass);
    }
}
