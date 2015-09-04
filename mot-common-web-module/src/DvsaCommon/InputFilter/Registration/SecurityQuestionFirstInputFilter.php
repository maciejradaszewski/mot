<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\InputFilter\Registration;

/**
 * (Account registration) First security question's step  input filter.
 *
 * Class SecurityQuestionFirstAbstractInputFilter
 */
class SecurityQuestionFirstInputFilter extends SecurityQuestionAbstractInputFilter
{
    /** Select a question to answer, in the first page */
    const FIELD_QUESTION = 'question1';
    const FIELD_ANSWER = 'answer1';
}
