<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\Boolean;

use Doctrineum\Boolean\BooleanEnum;
use Doctrineum\Tests\Scalar\Helpers\WithToStringTestObject;
use Granam\Boolean\BooleanInterface;
use PHPUnit\Framework\TestCase;

class BooleanEnumTest extends TestCase
{

    /**
     * @test
     */
    public function I_can_create_boolean_enum(): void
    {
        $instance = BooleanEnum::getEnum(true);
        self::assertInstanceOf(BooleanEnum::class, $instance);
        self::assertInstanceOf(BooleanInterface::class, $instance);
    }

    /**
     * @test
     * @dataProvider provideUsableValue
     * @param mixed $value
     * @param string $expectedAsString
     */
    public function I_will_get_the_same_value_as_boolean_as_created_with($value, string $expectedAsString): void
    {
        $enum = BooleanEnum::getEnum($value);
        self::assertSame((bool)$expectedAsString, $enum->getValue());
        self::assertSame($expectedAsString, (string)$enum);
    }

    public function provideUsableValue(): array
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
            [' ', '1'], // beware, space converted to boolean is true
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
    public function I_can_not_use_null(): void
    {
        BooleanEnum::getEnum(null);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_can_not_use_array(): void
    {
        BooleanEnum::getEnum([]);
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_can_not_use_resource(): void
    {
        BooleanEnum::getEnum(tmpfile());
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_can_not_use_object_without_to_string_method(): void
    {
        BooleanEnum::getEnum(new \stdClass());
    }

    /**
     * @test
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function callback_to_php_value_cause_exception(): void
    {
        BooleanEnum::getEnum(function () {
        });
    }
}