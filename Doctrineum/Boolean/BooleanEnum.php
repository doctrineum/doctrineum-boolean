<?php
namespace Doctrineum\Boolean;

use Doctrineum\Scalar\Enum;
use Granam\Boolean\Tools\ToBoolean;

/**
 * @method static BooleanEnum getEnum($value)
 * @method bool getValue()
 */
class BooleanEnum extends Enum implements BooleanEnumInterface
{

    /**
     * Overloading parent @see \Doctrineum\Scalar\EnumTrait::convertToEnumFinalValue
     * @param mixed $enumValue
     * @return int
     */
    protected static function convertToEnumFinalValue($enumValue)
    {
        return static::convertToBoolean($enumValue);
    }

    /**
     * @param mixed $valueToConvert
     * @return bool
     */
    protected static function convertToBoolean($valueToConvert)
    {
        try {
            return ToBoolean::toBoolean($valueToConvert);
        } catch (\Granam\Boolean\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping the exception by local one
            throw new Exceptions\UnexpectedValueToConvert($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

}
