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

    public function update($id, OrganisationDto $dto)
    {
        $url = AuthorisedExaminerUrlBuilder::of($id);

        return $this->put($url, DtoHydrator::dtoToJson($dto));
    }

    public function create(OrganisationDto $dto)
    {
        $url = AuthorisedExaminerUrlBuilder::of();

        return $this->post($url, DtoHydrator::dtoToJson($dto));
    }

    public function validate(OrganisationDto $dto)
    {
        $url = AuthorisedExaminerUrlBuilder::of();
        $dto->setIsValidateOnly(true);

        return $this->post($url, DtoHydrator::dtoToJson($dto));
    }

    public function status(OrganisationDto $dto, $id)
    {
        $url = AuthorisedExaminerUrlBuilder::status($id);

        return $this->put($url, DtoHydrator::dtoToJson($dto));
    }

    public function validateStatus(OrganisationDto $dto, $id)
    {
        $url = AuthorisedExaminerUrlBuilder::status($id);
        $dto->setIsValidateOnly(true);

        return $this->put($url, DtoHydrator::dtoToJson($dto));
    }
}
