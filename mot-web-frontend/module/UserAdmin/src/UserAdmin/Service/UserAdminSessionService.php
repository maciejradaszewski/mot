<?php

namespace UserAdmin\Service;

use Core\Service\SessionService;

class UserAdminSessionService extends SessionService
{
    const UNIQUE_KEY = 'useradmin';
}
