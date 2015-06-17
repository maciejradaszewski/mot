<?php

namespace DvsaCommon\Constants;

/**
 * Definition of MOT test number format. It must be 12-digits string. Must not start with "0".
 */
class MotTestNumberConstraint
{
    const FORMAT_REGEX = '[1-9][0-9]{11}';
}
