<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Boolean;

use Doctrineum\Scalar\ScalarEnumInterface;
use Doctrineum\Scalar\ScalarEnumType;
use Granam\Boolean\Tools\ToBoolean;

/**
 * @method static BooleanEnumType getType($name),
 */
class BooleanEnumType extends ScalarEnumType
{
    use BooleanEnumTypeTrait;

    public const BOOLEAN_ENUM = 'boolean_enum';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::BOOLEAN_ENUM;
    }

    /**
     * @see \Doctrineum\Scalar\ScalarEnumType::convertToPHPValue for usage
     *
     * @param mixed $enumValue
     * @return BooleanEnum
     * @throws \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     * @throws \Doctrineum\Scalar\Exceptions\CouldNotDetermineEnumClass
     * @throws \Doctrineum\Scalar\Exceptions\EnumClassNotFound
     */
    protected function convertToEnum($enumValue): ScalarEnumInterface
    {
        try {
            return parent::convertToEnum($this->convertToEnumValue($enumValue));
        } catch (\Doctrineum\Scalar\Exceptions\UnexpectedValueToConvert $exception) {
            // wrapping exception by local one
            throw new Exceptions\UnexpectedValueToConvert($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param $value
     * @return bool
     * @throws \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    protected function convertToEnumValue($value): bool
    {
        try {
            return ToBoolean::toBoolean($value, true /* strict */);
        } catch (\Granam\Boolean\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping exception by a local one
            throw new Exceptions\UnexpectedValueToConvert($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}