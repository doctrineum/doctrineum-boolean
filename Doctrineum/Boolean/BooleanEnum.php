<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Boolean;

use Doctrineum\Scalar\ScalarEnum;
use Granam\Boolean\Tools\ToBoolean;

/**
 * @method static BooleanEnum getEnum($value)
 */
class BooleanEnum extends ScalarEnum implements BooleanEnumInterface
{

    /**
     * Overloading parent @see \Doctrineum\Scalar\EnumTrait::convertToEnumFinalValue
     *
     * @param mixed $enumValue
     * @return bool
     * @throws \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    protected static function convertToEnumFinalValue($enumValue): bool
    {
        try {
            return ToBoolean::toBoolean($enumValue, true /* strict */);
        } catch (\Granam\Boolean\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping the exception by local one
            throw new Exceptions\UnexpectedValueToConvert($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @return bool
     */
    public function getValue(): bool
    {
        return parent::getValue();
    }
}