<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\InputFilter\Registration;

/**
 * (Account registration) Second security question's step  input filter.
 *
 * Class SecurityQuestionSecondAbstractInputFilter
 */
class SecurityQuestionSecondInputFilter extends SecurityQuestionAbstractInputFilter
{
    /** Select a question to answer, in the second page */
    const FIELD_QUESTION = 'question2';
    const FIELD_ANSWER = 'answer2';
}
