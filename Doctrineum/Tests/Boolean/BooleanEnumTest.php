<?php
namespace Doctrineum\Tests\Boolean;

use Doctrineum\Boolean\BooleanEnum;
use Doctrineum\Tests\Scalar\WithToStringTestObject;
use Granam\Boolean\BooleanInterface;

class BooleanEnumTest extends \PHPUnit_Framework_TestCase
{

    /** @test */
    public function I_can_create_boolean_enum()
    {
        $instance = BooleanEnum::getEnum(true);
        /** @var \PHPUnit_Framework_TestCase $this */
        self::assertInstanceOf(BooleanEnum::getClass(), $instance);
        self::assertInstanceOf(BooleanInterface::class, $instance);
    }

    /** @test */
    public function I_will_get_the_same_value_as_boolean_as_created_with()
    {
        $enum = BooleanEnum::getEnum($value = 1);
        self::assertSame((bool)$value, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($stringInteger = '123');
        self::assertSame((bool)$stringInteger, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($stringIntegerWithWhiteSpaces = '  12 ');
        self::assertSame((bool)$stringIntegerWithWhiteSpaces, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($float = 123.456);
        self::assertSame((bool)$float, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($stringFloat = '789.654');
        self::assertSame((bool)$stringFloat, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($integerZero = 0);
        self::assertSame((bool)$integerZero, $enum->getValue());
        self::assertSame(false, $enum->getValue());
        self::assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($stringIntegerZero = '0');
        self::assertSame((bool)$stringIntegerZero, $enum->getValue());
        self::assertSame(false, $enum->getValue());
        self::assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($floatZero = 0.0);
        self::assertSame((bool)$floatZero, $enum->getValue());
        self::assertSame(false, $enum->getValue());
        self::assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($stringFloatZero = '0.0');
        self::assertSame((bool)$stringFloatZero, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($emptyString = '');
        self::assertSame((bool)$emptyString, $enum->getValue());
        self::assertSame(false, $enum->getValue());
        self::assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($null = null);
        self::assertSame((bool)$null, $enum->getValue());
        self::assertSame(false, $enum->getValue());
        self::assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($space = ' ');
        self::assertSame((bool)$space, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($tab = "\t");
        self::assertSame((bool)$tab, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($newLine = "\n");
        self::assertSame((bool)$newLine, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($stringNumberWithLeadingZeros = '0123');
        self::assertSame((bool)$stringNumberWithLeadingZeros, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($stringWithLeadingZeros = '0abc');
        self::assertSame((bool)$stringWithLeadingZeros, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum(new WithToStringTestObject($integer = 12345));
        self::assertSame((bool)$integer, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum(new WithToStringTestObject($string = 'foo'));
        self::assertSame((bool)$string, $enum->getValue());
        self::assertTrue($enum->getValue());
        self::assertSame('1', "$enum");
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_can_not_use_array()
    {
        BooleanEnum::getEnum([]);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_can_not_use_resource()
    {
        BooleanEnum::getEnum(tmpfile());
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_can_not_use_object_without_to_string_method()
    {
        BooleanEnum::getEnum(new \stdClass());
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function callback_to_php_value_cause_exception()
    {
        BooleanEnum::getEnum(function () {
        });
    }
}
