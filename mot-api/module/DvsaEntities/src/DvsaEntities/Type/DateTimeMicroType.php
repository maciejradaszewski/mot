<?php

namespace DvsaEntities\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * This type is to enable read/write of datetime with microsecond precision.
 * Used for audit columns.
 */
class DateTimeMicroType extends Type
{
    private static $DT_MICRO_FORMAT = 'Y-m-d H:i:s.u';
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'datetimemicro';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? $value->format(self::$DT_MICRO_FORMAT) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $val = \DateTime::createFromFormat(self::$DT_MICRO_FORMAT, $value);
        if (!$val) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                self::$DT_MICRO_FORMAT
            );
        }

        return $val;
    }
}
