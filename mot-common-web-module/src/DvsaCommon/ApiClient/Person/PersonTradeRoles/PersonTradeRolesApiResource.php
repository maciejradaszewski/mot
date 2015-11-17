<?php

namespace DvsaCommon\ApiClient\Person\PersonTradeRoles;

use DvsaCommon\ApiClient\Person\PersonTradeRoles\Dto\PersonTradeRoleDto;
use DvsaCommon\HttpRestJson\AbstractApiResource;

class PersonTradeRolesApiResource extends AbstractApiResource
{
    public function getRoles($personId)
    {
        return $this->getMany(PersonTradeRoleDto::class, 'person/' . $personId . '/trade-role');
    }
}
