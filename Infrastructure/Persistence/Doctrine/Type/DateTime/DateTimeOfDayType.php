<?php

namespace Zelenin\Ddd\ValueObject\Infrastructure\Persistence\Doctrine\Type\DateTime;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\DateTimeOfDay;

class DateTimeOfDayType extends Type
{
    const NAME = 'datetime.datetimeofday';

    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     *
     * @throws DBALException
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     *
     * @return DateTimeOfDay
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return DateTimeOfDay::createFromString($value);
    }

    /**
     * @param DateTimeOfDay $value
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof DateTimeOfDay) {
            return $value->toDateTime()->format($platform->getDateTimeFormatString());
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }
}
