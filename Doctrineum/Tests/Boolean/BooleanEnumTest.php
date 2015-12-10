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
        $this->assertInstanceOf(BooleanEnum::getClass(), $instance);
        $this->assertInstanceOf(BooleanInterface::class, $instance);
    }

    /** @test */
    public function I_will_get_the_same_value_as_boolean_as_created_with()
    {
        $enum = BooleanEnum::getEnum($value = 1);
        $this->assertSame(boolval($value), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");
        
        $enum = BooleanEnum::getEnum($stringInteger = '123');
        $this->assertSame(boolval($stringInteger), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");
        
        $enum = BooleanEnum::getEnum($stringIntegerWithWhiteSpaces = '  12 ');
        $this->assertSame(boolval($stringIntegerWithWhiteSpaces), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($float = 123.456);
        $this->assertSame(boolval($float), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($stringFloat = '789.654');
        $this->assertSame(boolval($stringFloat), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($integerZero = 0);
        $this->assertSame(boolval($integerZero), $enum->getValue());
        $this->assertSame(false, $enum->getValue());
        $this->assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($stringIntegerZero = '0');
        $this->assertSame(boolval($stringIntegerZero), $enum->getValue());
        $this->assertSame(false, $enum->getValue());
        $this->assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($floatZero = 0.0);
        $this->assertSame(boolval($floatZero), $enum->getValue());
        $this->assertSame(false, $enum->getValue());
        $this->assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($stringFloatZero = '0.0');
        $this->assertSame(boolval($stringFloatZero), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($emptyString = '');
        $this->assertSame(boolval($emptyString), $enum->getValue());
        $this->assertSame(false, $enum->getValue());
        $this->assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($null = null);
        $this->assertSame(boolval($null), $enum->getValue());
        $this->assertSame(false, $enum->getValue());
        $this->assertSame('', "$enum");

        $enum = BooleanEnum::getEnum($space = ' ');
        $this->assertSame(boolval($space), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($tab = "\t");
        $this->assertSame(boolval($tab), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($newLine = "\n");
        $this->assertSame(boolval($newLine), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($stringNumberWithLeadingZeros = '0123');
        $this->assertSame(boolval($stringNumberWithLeadingZeros), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum($stringWithLeadingZeros = '0abc');
        $this->assertSame(boolval($stringWithLeadingZeros), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum(new WithToStringTestObject($integer = 12345));
        $this->assertSame(boolval($integer), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");

        $enum = BooleanEnum::getEnum(new WithToStringTestObject($string = 'foo'));
        $this->assertSame(boolval($string), $enum->getValue());
        $this->assertSame(true, $enum->getValue());
        $this->assertSame('1', "$enum");
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
        BooleanEnum::getEnum(function () {});
    }
}
