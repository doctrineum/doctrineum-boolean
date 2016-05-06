<?php
namespace Doctrineum\Tests\Boolean;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\Boolean\BooleanEnum;
use Doctrineum\Boolean\BooleanEnumType;
use Doctrineum\Scalar\ScalarEnumInterface;
use Doctrineum\Tests\Scalar\AbstractTypeTest;

class BooleanEnumTypeTest extends AbstractTypeTest
{

    protected function setUp()
    {
        $enumTypeClass = $this->getTypeClass();
        if (!Type::hasType($this->getExpectedTypeName())) {
            Type::addType($this->getExpectedTypeName(), $enumTypeClass);
        }
    }

    protected function tearDown()
    {
        $enumType = Type::getType($this->getExpectedTypeName());
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
     * @return BooleanEnumType
     */
    public function I_can_get_instance()
    {
        return parent::I_can_get_instance(); // wrapping parent to provide "proper" tests execution order
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends I_can_get_instance
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
     * @depends I_can_get_instance
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
        $enum = $this->mockery(ScalarEnumInterface::class);
        $enum->shouldReceive('getValue')
            ->once()
            ->andReturn($enumValue);

        $enumType = Type::getType($this->getExpectedTypeName());
        /** @var ScalarEnumInterface $enum */
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
     * @test
     * @dataProvider provideValueToConvertIntoEnum
     * @param $valueToConvert
     */
    public function I_get_enum_with_database_value($valueToConvert)
    {
        $enumType = Type::getType($this->getExpectedTypeName());
        $enum = $enumType->convertToPHPValue($valueToConvert, $this->getAbstractPlatform());
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
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
     * @depends I_can_get_instance
     */
    public function I_got_null_instead_on_enum_if_null_is_fetched_from_database(BooleanEnumType $booleanEnumType)
    {
        self::assertNull($booleanEnumType->convertToPHPValue(null, $this->getAbstractPlatform()));
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends I_can_get_instance
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_am_stopped_if_database_provides_invalid_value(BooleanEnumType $enumType)
    {
        $enumType->convertToPHPValue(new \stdClass(), $this->getAbstractPlatform());
    }

    /**
     * @test
     */
    public function I_can_add_boolean_subtypes()
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
