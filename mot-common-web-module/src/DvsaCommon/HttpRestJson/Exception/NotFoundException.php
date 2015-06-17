<?php

namespace DvsaCommon\HttpRestJson\Exception;

/**
 * Thrown when 404 was sent by the API response (except for general 404s caused by
 * misconfiguration etc.)
 *
 * It is not expected that the user can do anything about this, so they
 * should be shown a generic 404 page.
 */
class NotFoundException extends GeneralRestException
{
}
