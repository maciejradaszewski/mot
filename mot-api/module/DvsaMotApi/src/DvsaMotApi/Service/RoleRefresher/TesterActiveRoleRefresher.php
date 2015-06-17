<?php

namespace DvsaMotApi\Service\RoleRefresher;

use DvsaMotApi\Service\TesterService;

/**
 * Class TesterActiveRoleRefresher
 *
 * @package DvsaMotApi\Service\RoleRefresher
 */
class TesterActiveRoleRefresher implements RoleRefresherInterface
{
    /**
     * @var TesterService $testerService
     */
    private $testerService;

    public function __construct(TesterService $testerService)
    {
        $this->testerService = $testerService;
    }

    public function refresh($userId)
    {
        if ($this->testerService->isTester($userId)) {
            return $this->testerService->verifyAndApplyTesterIsActiveByUserId($userId);
        }

        return false;
    }
}
