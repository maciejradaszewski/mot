<?php

namespace DvsaCommon\Auth;

/**
 * Thrown when the user attempts to access a page for which being logged in is required.
 */
class NotLoggedInException extends \Exception
{
}
