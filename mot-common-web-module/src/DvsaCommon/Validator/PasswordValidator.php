<?php

namespace DvsaCommon\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Password validator.
 *
 * Rules enforced here should match the OpenDJ Password Policy found here
 * {@link https://wiki.i-env.net/display/MP/OpenAM+Installation+and+Configuration}.
 *
 * The relevant segment opf the password policy is displayed below:
 * <code>
 *  --set character-set:0:abcdefghijklmnopqrstuvwxyz \
 *  --set character-set:0:ABCDEFGHIJKLMNOPQRSTUVWXYZ  \
 *  --set character-set:0:0123456789  \
 *  --set character-set:0:\!\?\-\_\(\)\:\=\" \
 *  --set min-character-sets:3 \
 * </code>
 */
class PasswordValidator extends AbstractValidator
{
    /**
     * Default minimum length of characters
     * @var int
     */
    public $min = 8;

    /**
     * Default maximum length of characters
     * @var int
     */
    public $max = 32;

    /**
     * Default minimum length of numbers
     * @var
     */
    public $minDigit = 1;

    const MSG_DIGIT                         = 'msgDigit';
    const MSG_UPPER_AND_LOWERCASE           = 'msgUpperAndLowerCase';
    const MSG_MIN_CHAR                      = 'msgMinChar';
    const MSG_MAX_CHAR                      = 'msgMaxChar';
    const MSG_SPECIAL_CHARS                 = 'msgSpecialChar';

    // OpenDJ Character Sets (1-4)
    const OPENDJ_CS_1_LOWERCASE_CHARS       = 'abcdefghijklmnopqrstuvwxyz';
    const OPENDJ_CS_1_LOWERCASE_CHARS_REGEX = '/[a-z]/';
    const OPENDJ_CS_2_UPPERCASE_CHARS       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const OPENDJ_CS_2_UPPERCASE_CHARS_REGEX = '/[A-Z]/';
    const OPENDJ_CS_3_DIGITS                = '0123456789';
    const OPENDJ_CS_3_DIGITS_REGEX_TEMPLATE = '/\d{%s}/';
    const OPENDJ_CS_4_SPECIAL_CHARS         = '!?-_:="()';
    const OPENDJ_CS_4_SPECIAL_CHARS_REGEX   = '/[^\w!\?\-\_\:\=\"\(\)]/';

    /**
     * @var array
     */
    protected $messageVariables = [
        'minChar' => 'min',
        'maxChar' => 'max',
        'minNumber' => 'minDigit'
    ];

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::MSG_DIGIT                 => 'must contain %minNumber%, or more, numbers',
        self::MSG_MIN_CHAR              => 'must be %minChar% or more, characters long',
        self::MSG_UPPER_AND_LOWERCASE   => 'must contain both upper and lower case letters',
        self::MSG_MAX_CHAR              => 'must be less than %maxChar% characters long',
        self::MSG_SPECIAL_CHARS         => 'can only contain letters, numbers and the following symbols ( ) ! ? - _ : = "',
    ];


    /**
     * @param integer $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * @param integer $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @param integer $minDigit
     */
    public function setMinDigit($minDigit)
    {
        $this->minDigit = $minDigit;
    }

    /**
     * Note we are not strictly following the OpenDJ Password Policy. This policy requires 3 out of 4 character sets
     * (see the class header to learn about character sets) to be used in the password. Here we are requiring the
     * first 3 characters all the time, hence fulfilling the policy, but not allowing the user to chose 3 out of the
     * 4 available. Also there is no rule currently in the policy for maximum length of the password.
     *
     * In short, this password validator is more strict than the OpenDJ password policy.
     *
     * In the long run we should validate directly with OpenDJ/OpenAM.
     *
     * @param string $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);
        $isValid = true;

        if (strlen($value) < $this->min) {
            $this->error(self::MSG_MIN_CHAR);
            $isValid = false;
        }

        if (strlen($value) > $this->max) {
            $this->error(self::MSG_MAX_CHAR);
            $isValid = false;
        }

        if (!preg_match(self::OPENDJ_CS_1_LOWERCASE_CHARS_REGEX, $value)) {
            $this->error(self::MSG_UPPER_AND_LOWERCASE);
            $isValid = false;
        }

        if (!preg_match(self::OPENDJ_CS_2_UPPERCASE_CHARS_REGEX, $value)) {
            $this->error(self::MSG_UPPER_AND_LOWERCASE);
            $isValid = false;
        }

        if (!preg_match($this->getOpenDjCs3DigitsRegex(), $value)) {
            $this->error(self::MSG_DIGIT);
            $isValid = false;
        }

        if (preg_match(self::OPENDJ_CS_4_SPECIAL_CHARS_REGEX, $value)) {
            $this->error(self::MSG_SPECIAL_CHARS);
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * Prepare required regex to validate minimum required length of numbers
     * @return string
     */
    private function getOpenDjCs3DigitsRegex()
    {
        return sprintf(self::OPENDJ_CS_3_DIGITS_REGEX_TEMPLATE, $this->minDigit);
    }
}
