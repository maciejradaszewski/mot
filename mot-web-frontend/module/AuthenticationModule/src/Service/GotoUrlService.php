<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule\Service;

/**
 * Encapsulates functionality related to goto parameter used for redirecting to the right screen.
 */
class GotoUrlService
{
    /**
     * @var GotoUrlValidatorService
     */
    protected $gotoUrlValidator;

    /**
     * @param GotoUrlValidatorService $gotoUrlValidator
     */
    public function __construct(GotoUrlValidatorService $gotoUrlValidator)
    {
        $this->gotoUrlValidator = $gotoUrlValidator;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function isValidGoto($url)
    {
        return $this->gotoUrlValidator->isValid($url);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public function encodeGoto($url)
    {
        return $this->isValidGoto($url) ? base64_encode($url) : '';
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public function decodeGoto($url)
    {
        $decodedUrl = base64_decode($url);

        return $this->isValidGoto($decodedUrl) ? $decodedUrl : '';
    }
}
