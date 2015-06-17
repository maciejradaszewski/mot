<?php
namespace DvsaCommon\Date;

use DvsaCommon\Utility\TypeCheck;

class Time
{
    private $hour;
    private $minute;
    private $second;

    private static $splitIsoRegex = '/([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])/';

    public function getHour()
    {
        return $this->hour;
    }

    public function getMinute()
    {
        return $this->minute;
    }

    public function getSecond()
    {
        return $this->second;
    }

    public function toTimestamp()
    {
        return $this->getHour() * 3600
        + $this->getMinute() * 60
        + $this->getSecond();
    }

    public function toTimestamp24()
    {
        $stdTs = $this->toTimestamp();
        return ($stdTs === 0) ? 24 * 3600 : $stdTs;
    }

    public function toIso8601()
    {
        return gmdate("H:i:s", $this->toTimestamp());
    }

    private function toDateTime()
    {
        return new \DateTime('today '.$this->toIso8601());
    }

    public function format($format)
    {
        return $this->toDateTime()->format($format);
    }

    public function __construct($hour, $minute, $second)
    {
        if (!self::isValid($hour, $minute, $second)) {
            throw new \InvalidArgumentException(
                "Cannot create time from values: H:" . $hour . " M: " . $minute . " S: " . $second
            );
        }

        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    public function equals(Time $other)
    {
        return $this == $other;
    }

    public function greaterThan(Time $other)
    {
        return $this->toTimestamp() > $other->toTimestamp();
    }

    public function greaterEqualThan(Time $other)
    {
        return $this->greaterThan($other) || $this->equals($other);
    }

    public function lesserThan(Time $other)
    {
        return $other->greaterThan($this);
    }

    public function lesserEqualThan(Time $other)
    {
        return $this->lesserThan($other) || $this->equals($other);
    }

    public static function isValid($hour, $minute, $second)
    {
        if (!TypeCheck::isInteger($hour) || $hour < 0 || $hour > 23) {
            return false;
        }

        if (!TypeCheck::isInteger($minute) || $minute < 0 || $minute > 59) {
            return false;
        }

        if (!TypeCheck::isInteger($second) || $second < 0 || $second > 59) {
            return false;
        }

        return true;
    }

    public static function now()
    {
        return self::fromDateTime(new \DateTime());
    }

    /**
     * Will get the time part of the date time object, will drop year, month, day.
     *
     * @param \DateTime $dateTime
     *
     * @return Time
     */
    public static function fromDateTime(\DateTime $dateTime)
    {
        $hour = $dateTime->format('H');
        $minute = $dateTime->format('i');
        $second = $dateTime->format('s');

        return new Time($hour, $minute, $second);
    }

    public static function fromTimestamp($timestamp)
    {
        if (!self::isValidTimestamp($timestamp)) {
            throw new \InvalidArgumentException("Cannot create time from timestamp: '" . $timestamp . "'");
        }

        return Time::fromDateTime(new \DateTime('@' . $timestamp));
    }

    public static function isValidTimestamp($timestamp)
    {
        return TypeCheck::isInteger($timestamp) && $timestamp < 86400 && $timestamp >= 0;
    }

    public static function fromIso8601($iso8601)
    {
        if (!self::isValidIso8601($iso8601)) {
            throw new \InvalidArgumentException(
                'Invalid value " ' . $iso8601 . ' " provided. Provide 00:00:00 format.'
            );
        }

        preg_match(self::$splitIsoRegex, $iso8601, $matches);

        $hours = $matches[1];
        $minutes = $matches[2];
        $seconds = $matches[3];

        return new Time($hours, $minutes, $seconds);
    }

    public static function isValidIso8601($iso8601)
    {
        return preg_match(self::$splitIsoRegex, $iso8601);
    }

    public function isAm()
    {
        return $this->getHour() < 12;
    }

    public function isPm()
    {
        return !$this->isAm();
    }
}
