<?php

namespace DvsaMotApi\Service;

use DvsaMotApi\Service\RoleRefresher\RoleRefresherInterface;

/**
 * Class RoleRefreshService
 *
 * @package DvsaMotApi\Service
 */
class RoleRefreshService
{

    /**
     * @var array
     */
    private $roleRefreshers;

    public function __construct(array $roleRefreshers)
    {
        $this->roleRefreshers = $roleRefreshers;
    }

    public function refreshRoles($userId)
    {
        $rolesChanged = false;

        /** @var RoleRefresherInterface $roleRefresher */
        foreach ($this->roleRefreshers as $roleRefresher) {
            if ($roleRefresher->refresh($userId)) {
                $rolesChanged = true;
            }
        }
        return $rolesChanged;
    }
}
