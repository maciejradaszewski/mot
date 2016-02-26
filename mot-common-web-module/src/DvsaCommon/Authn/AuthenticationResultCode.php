<?php

namespace DvsaCommon\Authn;

class AuthenticationResultCode
{
    const SUCCESS = 100;
    const INVALID_CREDENTIALS = -101;
    const LOCKOUT_WARNING = -102;
    const ACCOUNT_LOCKED = -103;
    const UNRESOLVABLE_IDENTITY = -104;
    const ERROR = -199;
}