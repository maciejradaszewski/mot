<?php

namespace DvsaClient\Mapper;

use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;

/**
 * Class OrganisationDtoMapper
 *
 * @package DvsaClient\Mapper
 */
class OrganisationMapper extends DtoMapper
{
    /**
     * @param $managerId
     *
     * @return OrganisationDto[]
     */
    public function fetchAllForManager($managerId)
    {
        $url = PersonUrlBuilder::byId($managerId)->authorisedExaminer();
        return $this->get($url);
    }

    /**
     * @param $id
     *
     * @return OrganisationDto
     */
    public function getAuthorisedExaminer($id)
    {
        $url = AuthorisedExaminerUrlBuilder::of($id);
        return $this->get($url);
    }

    /**
     * @param array $params
     *
     * @return OrganisationDto
     */
    public function getAuthorisedExaminerByNumber($params)
    {
        $url = AuthorisedExaminerUrlBuilder::of()->authorisedExaminerByNumber();
        return $this->getWithParams($url, $params);
    }

    public function updateAuthorisedExaminer($id, OrganisationDto $dto)
    {
        $url = AuthorisedExaminerUrlBuilder::of($id);

        return $this->put($url, DtoHydrator::dtoToJson($dto));
    }
}
