<?php

namespace Core\Presenter;

/**
 * Class MastheadPresenter
 */
class MastheadPresenter
{
    /**
     * The email address that mailto URI's should include
     * @var string
     */
    private $emailAddress = 'mot.modernisation@vosa.gsi.gov.uk';

    /**
     * The email subject line to include in mailto links
     * @var string
     */
    private $subjectLine = 'MOT testing service feedback';

    /**
     * Return the mailto URI for the feedback link in the masthead
     * @return string
     */
    public function getFeedbackMailtoUri()
    {
        return 'mailto:' . $this->emailAddress . '?subject=' . rawurlencode($this->subjectLine);
    }
}
