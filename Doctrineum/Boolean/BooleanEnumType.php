<?php
namespace Doctrineum\Boolean;

use Doctrineum\Scalar\ScalarEnumType;
use Granam\Boolean\Tools\ToBoolean;

/**
 * @method static BooleanEnumType getType($name),
 */
class BooleanEnumType extends ScalarEnumType
{
    use BooleanEnumTypeTrait;

    const BOOLEAN_ENUM = 'boolean_enum';

    /**
     * @return string
     */
    public function getName()
    {
        return self::BOOLEAN_ENUM;
    }

    /**
     * @see \Doctrineum\Scalar\ScalarEnumType::convertToPHPValue for usage
     *
     * @param mixed $enumValue
     *
     * @return BooleanEnum|null
     * @throws \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     * @throws \Doctrineum\Scalar\Exceptions\CouldNotDetermineEnumClass
     * @throws \Doctrineum\Scalar\Exceptions\EnumClassNotFound
     */
    protected function convertToEnum($enumValue)
    {
        try {
            return parent::convertToEnum($this->convertToEnumValue($enumValue));
        } catch (\Doctrineum\Scalar\Exceptions\UnexpectedValueToConvert $exception) {
            // wrapping exception by local one
            throw new Exceptions\UnexpectedValueToConvert(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param $value
     * @return bool
     * @throws \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    protected function convertToEnumValue($value)
    {
        try {
            return ToBoolean::toBoolean($value, true /* strict */);
        } catch (\Granam\Boolean\Tools\Exceptions\WrongParameterType $exception) {
            // wrapping exception by a local one
            throw new Exceptions\UnexpectedValueToConvert($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
