<?php

namespace DvsaCommon\Constants;

/**
 * Class Network
 *
 * This class contains any network specific / related constants that
 * don't have a home in any more suitable place in the hierarchy.
 */
class Network
{
    /**
     * When detecting a Client IP address during MOT test creation, this
     * value is used to indicate that real IP address was not available.
     */
    const DEFAULT_CLIENT_IP = '0.0.0.0';
}
