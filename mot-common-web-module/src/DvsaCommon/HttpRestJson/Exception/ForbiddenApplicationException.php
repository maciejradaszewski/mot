<?php

namespace DvsaCommon\HttpRestJson\Exception;

/**
 * Covers HTTP 403 exceptions where the the user did/typed in something wrong
 * and therefore it's recoverable.
 */
class ForbiddenApplicationException extends RestApplicationException
{
}
