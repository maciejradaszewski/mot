<?php

namespace DvsaCommon\HttpRestJson\Exception;

/**
 * Covers HTTP 401 and 403 exceptions where the the user entered an incorrect one time password
 * and therefore it's recoverable.
 */
class OtpApplicationException extends RestApplicationException
{
}
