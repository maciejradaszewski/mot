<?php

namespace SiteApi\Model;

/**
 * Class to generate number for a new site
 */
class SiteNumberGenerator
{
    public function generate($siteId)
    {
        return sprintf('S%06d', (string)$siteId);
    }
}
