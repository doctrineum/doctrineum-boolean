<?php
namespace Doctrineum\Boolean;

use Doctrineum\Scalar\EnumType;
use Granam\Boolean\Tools\ToBoolean;

/**
 * Class EnumType
 * @package Doctrineum
 *
 * @method static BooleanEnumType getType($name),
 * @see Type::getType
 */
class BooleanEnumType extends EnumType
{
    use BooleanEnumTypeTrait;

    const BOOLEAN_ENUM = 'boolean_enum';

    /**
     * @see \Doctrineum\Scalar\EnumType::convertToPHPValue for usage
     *
     * @param mixed $enumValue
     *
     * @return BooleanEnum
     */
    protected function convertToEnum($enumValue)
    {
        $this->checkValueToConvert($enumValue);

        return parent::convertToEnum($enumValue);
    }

    protected function checkValueToConvert($value)
    {
        try {
            // Uses side effect of the conversion - the checks
            ToBoolean::toBoolean($value);
        } catch (\Granam\Boolean\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping exception by a local one
            throw new Exceptions\UnexpectedValueToConvert($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
