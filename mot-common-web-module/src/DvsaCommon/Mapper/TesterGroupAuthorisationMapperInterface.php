<?php
namespace DvsaCommon\Mapper;

interface TesterGroupAuthorisationMapperInterface
{
    const DEFAULT_NO_STATUS = 'Not Applied';

    /**
     * @param int $personId
     */
    public function getAuthorisation($personId);
}
