<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response;

/**
 * Interface OpenAMAuthenticationResponse.
 */
interface OpenAMAuthenticationResponse
{
    /**
     * @return bool
     */
    public function isSuccess();
}
