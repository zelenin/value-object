<?php

namespace Zelenin\Ddd\ValueObject\Infrastructure\Persistence\Doctrine\Type\DateTime;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Zelenin\Ddd\ValueObject\Domain\Model\DateTime\Point\Calendar\Date;

class DateType extends Type
{
    const NAME = 'datetime.date';

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
     * @return Date
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Date::createFromString($value);
    }

    /**
     * @param Date $value
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Date) {
            return $value->toDateTime()->format('Y-m-d H:i:s');
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
