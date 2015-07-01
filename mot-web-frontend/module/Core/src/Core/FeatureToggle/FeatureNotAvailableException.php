<?php
/**
 * This file is part of the DVSA MOT Frontend package.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Core\FeatureToggle;

use Exception;
use RuntimeException;

/**
 * FeatureNotAvailableException is used to signal the client that a feature is not available or is disabled in the
 * FeatureToggles service.
 */
class FeatureNotAvailableException extends RuntimeException
{
    /**
     * @var string
     */
    private $featureName;

    /**
     * Construct the exception.
     *
     * @param string    $featureName The name of the feature that triggered this exception.
     * @param string    $message     [optional] The Exception message to throw.
     * @param int       $code        [optional] The Exception code.
     * @param Exception $previous    [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($featureName, $message = "", $code = 0, Exception $previous = null)
    {
        $this->featureName = $featureName;
        if (!$message) {
            $message = sprintf('Feature "%s" is either disabled or not available in the current application configuration.',
                $featureName);
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getFeatureName()
    {
        return $this->featureName;
    }
}
