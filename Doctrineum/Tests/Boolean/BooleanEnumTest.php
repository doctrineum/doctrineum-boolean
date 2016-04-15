<?php
namespace Doctrineum\Tests\Boolean;

use Doctrineum\Boolean\BooleanEnum;
use Doctrineum\Tests\Scalar\WithToStringTestObject;
use Granam\Boolean\BooleanInterface;

class BooleanEnumTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function I_can_create_boolean_enum()
    {
        $instance = BooleanEnum::getEnum(true);
        /** @var \PHPUnit_Framework_TestCase $this */
        self::assertInstanceOf(BooleanEnum::getClass(), $instance);
        self::assertInstanceOf(BooleanInterface::class, $instance);
    }

    /**
     * @test
     * @dataProvider provideUsableValue
     * @param mixed $value
     * @param string $expectedString
     */
    public function I_will_get_the_same_value_as_boolean_as_created_with($value, $expectedString)
    {
        $enum = BooleanEnum::getEnum($value);
        self::assertSame((bool)$expectedString, $enum->getValue());
        self::assertSame($expectedString, "$enum");
    }

    public function provideUsableValue()
    {
        return [
            [1, '1'],
            ['123', '1'],
            ['  12 ', '1'],
            [123.456, '1'],
            ['789.654', '1'],
            [0, ''],
            ['0', ''],
            [0.0, ''],
            ['0.0', '1'],
            ['', ''],
            [' ', '1'],
            ["\t", '1'],
            ["\n", '1'],
            ["\r", '1'],
            ['0123', '1'],
            ['0abc', '1'],
            [new WithToStringTestObject(12345), '1'],
            [new WithToStringTestObject('foo'), '1'],
        ];
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     * @expectedExceptionMessageRegExp ~got NULL$~
     */
    public function I_can_not_use_null()
    {
        BooleanEnum::getEnum(null);
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
