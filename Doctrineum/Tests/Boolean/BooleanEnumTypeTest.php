<?php
namespace Doctrineum\Tests\Boolean;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\Boolean\BooleanEnum;
use Doctrineum\Boolean\BooleanEnumType;
use Doctrineum\Scalar\Enum;
use Granam\Tests\Tools\TestWithMockery;

class BooleanEnumTypeTest extends TestWithMockery
{

    protected function setUp()
    {
        $enumTypeClass = $this->getEnumTypeClass();
        if (!Type::hasType($enumTypeClass::getTypeName())) {
            Type::addType($enumTypeClass::getTypeName(), $enumTypeClass);
        }
    }

    /**
     * @return \Doctrineum\Boolean\BooleanEnumType
     */
    protected function getEnumTypeClass()
    {
        return BooleanEnumType::getClass();
    }

    protected function tearDown()
    {
        $enumTypeClass = $this->getEnumTypeClass();
        $enumType = Type::getType($enumTypeClass::getTypeName());
        /** @var BooleanEnumType $enumType */
        if ($enumType::hasSubTypeEnum(TestSubTypeBooleanEnum::getClass())) {
            self::assertTrue($enumType::removeSubTypeEnum(TestSubTypeBooleanEnum::getClass()));
        }
        if ($enumType::hasSubTypeEnum(TestAnotherSubTypeBooleanEnum::getClass())) {
            self::assertTrue($enumType::removeSubTypeEnum(TestAnotherSubTypeBooleanEnum::getClass()));
        }
        parent::tearDown();
    }

    /**
     * @test
     */
    public function I_can_register_it()
    {
        $enumTypeClass = $this->getEnumTypeClass();
        if (!Type::hasType($enumTypeClass::getTypeName())) {
            Type::addType($enumTypeClass::getTypeName(), $enumTypeClass);
        }
        self::assertTrue(Type::hasType($enumTypeClass::getTypeName()));
    }

    /**
     * @test
     */
    public function I_can_get_instance_of_it()
    {
        $enumTypeClass = $this->getEnumTypeClass();
        $instance = $enumTypeClass::getType($enumTypeClass::getTypeName());
        self::assertInstanceOf($enumTypeClass, $instance);

        return $instance;
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends I_can_get_instance_of_it
     */
    public function I_get_expected_type_name(BooleanEnumType $enumType)
    {
        $enumTypeClass = $this->getEnumTypeClass();
        $typeName = $this->convertToTypeName($enumTypeClass);
        $constantName = strtoupper($typeName);
        self::assertTrue(defined("$enumTypeClass::$constantName"));
        self::assertSame($enumTypeClass::getTypeName(), $typeName);
        self::assertSame($typeName, constant("$enumTypeClass::$constantName"));
        self::assertSame($enumType::getTypeName(), $enumTypeClass::getTypeName());
    }

    /**
     * @param string $className
     *
     * @return string
     */
    private function convertToTypeName($className)
    {
        $withoutType = preg_replace('~Type$~', '', $className);
        $parts = explode('\\', $withoutType);
        $baseClassName = $parts[count($parts) - 1];
        preg_match_all('~(?<words>[A-Z][^A-Z]+)~', $baseClassName, $matches);
        $concatenated = implode('_', $matches['words']);

        return strtolower($concatenated);
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends I_can_get_instance_of_it
     */
    public function Its_sql_declaration_is_valid(BooleanEnumType $enumType)
    {
        $sql = $enumType->getSQLDeclaration([], $this->getAbstractPlatform());
        self::assertSame('INTEGER', $sql);
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends I_can_get_instance_of_it
     */
    public function Its_sql_default_length_is_one(BooleanEnumType $enumType)
    {
        $defaultLength = $enumType->getDefaultLength($this->getAbstractPlatform());
        self::assertSame(1, $defaultLength);
    }

    /**
     * @return AbstractPlatform
     */
    private function getAbstractPlatform()
    {
        return $this->mockery(AbstractPlatform::class);
    }

    /**
     * @param $enumValue
     *
     * @test
     * @dataProvider provideEnumValueForDatabase
     */
    public function Its_persisted_with_equal_value_as_enum_has($enumValue)
    {
        $enum = $this->mockery(Enum::class);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $enum->shouldReceive('getValue')
            ->once()
            ->andReturn($enumValue);

        $enumType = Type::getType(BooleanEnumType::getTypeName());
        /** @var Enum $enum */
        self::assertSame($enumValue, $enumType->convertToDatabaseValue($enum, $this->getAbstractPlatform()));
    }

    public function provideEnumValueForDatabase()
    {
        return [
            [0],
            [1]
        ];
    }

    /**
     * CONVERSIONS TO PHP VALUE
     */

    /**
     * @test
     * @dataProvider provideValueToConvertIntoEnum
     * @param $valueToConvert
     */
    public function I_get_enum_with_database_value($valueToConvert)
    {
        $enumType = Type::getType(BooleanEnumType::getTypeName());
        $enum = $enumType->convertToPHPValue($valueToConvert, $this->getAbstractPlatform());
        self::assertInstanceOf($this->getRegisteredEnumClass(), $enum);
        self::assertSame((bool)$valueToConvert, $enum->getValue());
        self::assertSame((string)(bool)$valueToConvert, (string)$enum);
    }

    public function provideValueToConvertIntoEnum()
    {
        return [
            [123],
            [0],
        ];
    }

    /**
     * @param BooleanEnumType $booleanEnumType
     * @test
     * @depends I_can_get_instance_of_it
     */
    public function I_got_null_instead_on_enum_if_fetched_from_database(BooleanEnumType $booleanEnumType)
    {
        self::assertNull($booleanEnumType->convertToPHPValue(null, $this->getAbstractPlatform()));
    }

    /**
     * @return \Doctrineum\Boolean\BooleanEnum
     */
    protected function getRegisteredEnumClass()
    {
        return BooleanEnum::getClass();
    }

    /**
     * @test
     */
    public function I_can_register_another_enum_type()
    {
        /** @var TestAnotherBooleanEnumType $anotherEnumType */
        $anotherEnumType = TestAnotherBooleanEnumType::getClass();
        if (!$anotherEnumType::isRegistered()) {
            self::assertTrue($anotherEnumType::registerSelf());
        } else {
            self::assertFalse($anotherEnumType::registerSelf());
        }

        self::assertTrue($anotherEnumType::isRegistered());
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends I_can_get_instance_of_it
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_am_stopped_if_use_invalid_value(BooleanEnumType $enumType)
    {
        $enumType->convertToPHPValue(new \stdClass(), $this->getAbstractPlatform());
    }

    /**
     * @test
     */
    public function I_can_add_subtypes()
    {
        self::assertTrue(
            BooleanEnumType::addSubTypeEnum(TestSubTypeBooleanEnum::getClass(), '~1~')
        );
        self::assertTrue(
            BooleanEnumType::hasSubTypeEnum(TestSubTypeBooleanEnum::getClass())
        );

        self::assertTrue(
            BooleanEnumType::addSubTypeEnum(TestAnotherSubTypeBooleanEnum::getClass(), '~1~')
        );
        self::assertTrue(
            BooleanEnumType::hasSubTypeEnum(TestAnotherSubTypeBooleanEnum::getClass())
        );
    }

}

/** inner */
class TestSubTypeBooleanEnum extends BooleanEnum
{

}

class TestAnotherSubTypeBooleanEnum extends BooleanEnum
{

}

class TestAnotherBooleanEnumType extends BooleanEnumType
{

}
