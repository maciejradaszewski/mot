<?php

namespace DvsaMotTest\Mapper;

use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationDtoInterface;
use DvsaCommon\Dto\Common\MotTestDto;

/**
 * Maps form data to BrakeTestConfiguration Dto
 */
interface BrakeTestConfigurationMapperInterface
{
    /**
     * @param MotTestDto $motTest
     *
     * @return BrakeTestConfigurationDtoInterface
     */
    public function mapToDefaultDto(MotTestDto $motTest);

    /**
     * @param array $data
     *
     * @return BrakeTestConfigurationDtoInterface
     */
    public function mapToDto($data);
}
