<?php
namespace Api\Check;


/**
 * Encapsulates information about messages occuring in the system being a result
 * of various checks, validation or verification procedures.
 *
 * Class CheckMessage
 * @package Api\Check
 */
class CheckMessage
{
    /**
     * @var integer $code
     */
    private $code;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     * @see Severity
     */
    private $severity = Severity::ERROR;
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $fieldContext;

    /**
     * @param string|null $fieldContext
     */
    private function __construct($fieldContext = null)
    {
        $this->fieldContext = $fieldContext;
    }

    /**
     * Factory method creating an error message
     * @return $this
     */
    public static function withError()
    {
        return CheckMessage::create()->severity(Severity::ERROR);
    }

    /**
     * Factory method creating an info message
     * @return $this
     */
    public static function withInfo()
    {
        return CheckMessage::create()->severity(Severity::INFO);
    }

    /**
     * Factory method creating a warn message
     * @return $this
     */
    public static function withWarn()
    {
        return CheckMessage::create()->severity(Severity::WARN);
    }

    /**
     * Factory method creating a message with text
     * @param $text
     * @return $this
     */
    public static function withText($text)
    {
        return CheckMessage::create()->text($text);
    }

    /**
     * @param string $fieldContext
     *
     * @return CheckMessage
     */
    public static function create($fieldContext = null)
    {
        return new CheckMessage($fieldContext);
    }

    /**
     * Sets a field which the check was done on (if any)
     * @param string $field
     * @return $this
     */
    public function field($field)
    {
        $this->field = $this->fieldContext ? $this->fieldContext . "." . $field : $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Sets a code of the message
     * @param integer $code
     * @return $this
     */
    public function code($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function text($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @see Severity
     * @param $severity
     * @return $this
     */
    public function severity($severity)
    {
        $this->severity = $severity;
        return $this;
    }

    /**
     * @see Severity
     * @return string
     */
    public function getSeverity()
    {
        return $this->severity;
    }
}
