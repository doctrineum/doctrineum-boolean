<?php
namespace Doctrineum\Boolean;

use Doctrineum\Scalar\ScalarEnum;
use Granam\Boolean\Tools\ToBoolean;

/**
 * @method static BooleanEnum getEnum($value)
 * @method bool getValue()
 */
class BooleanEnum extends ScalarEnum implements BooleanEnumInterface
{

    /**
     * Overloading parent @see \Doctrineum\Scalar\EnumTrait::convertToEnumFinalValue
     * @param mixed $enumValue
     * @return bool
     * @throws \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    protected static function convertToEnumFinalValue($enumValue)
    {
        try {
            return ToBoolean::toBoolean($enumValue, true /* strict */);
        } catch (\Granam\Boolean\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping the exception by local one
            throw new Exceptions\UnexpectedValueToConvert($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
