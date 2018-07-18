<?php
declare(strict_types=1);

namespace Doctrineum\Boolean;

use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * @method integer convertToDatabaseValue(BooleanEnumType $enumValue, AbstractPlatform $platform)
 * @see \Doctrineum\Scalar\ScalarEnumType::convertToDatabaseValue
 */
trait BooleanEnumTypeTrait
{

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(
        /** @noinspection PhpUnusedParameterInspection */
        array $fieldDeclaration,
        AbstractPlatform $platform
    ): string
    {
        return 'INTEGER';
    }

    /**
     * Just for your information, is not used at code.
     * Maximum length of default SQL integer, @link http://en.wikipedia.org/wiki/Integer_%28computer_science%29
     *
     * @param AbstractPlatform $platform
     * @return int
     */
    public function getDefaultLength(
        /** @noinspection PhpUnusedParameterInspection */
        AbstractPlatform $platform
    ): int
    {
        return 1;
    }
}