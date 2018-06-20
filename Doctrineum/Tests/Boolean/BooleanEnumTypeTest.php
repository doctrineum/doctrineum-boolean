<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace Doctrineum\Tests\Boolean;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\Boolean\BooleanEnum;
use Doctrineum\Boolean\BooleanEnumType;
use Doctrineum\Scalar\ScalarEnumInterface;
use Doctrineum\Tests\SelfRegisteringType\AbstractSelfRegisteringTypeTest;

class BooleanEnumTypeTest extends AbstractSelfRegisteringTypeTest
{

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp(): void
    {
        $enumTypeClass = $this->getTypeClass();
        if (!Type::hasType($this->getExpectedTypeName())) {
            Type::addType($this->getExpectedTypeName(), $enumTypeClass);
        }
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function tearDown(): void
    {
        $enumType = Type::getType($this->getExpectedTypeName());
        /** @var BooleanEnumType $enumType */
        if ($enumType::hasSubTypeEnum(TestSubTypeBooleanEnum::class)) {
            self::assertTrue($enumType::removeSubTypeEnum(TestSubTypeBooleanEnum::class));
        }
        if ($enumType::hasSubTypeEnum(TestAnotherSubTypeBooleanEnum::class)) {
            self::assertTrue($enumType::removeSubTypeEnum(TestAnotherSubTypeBooleanEnum::class));
        }
        parent::tearDown();
    }

    /**
     * @test
     * @return BooleanEnumType
     */
    public function I_can_get_instance(): BooleanEnumType
    {
        return parent::I_can_get_instance(); // wrapping parent to provide "proper" tests execution order
    }

    /**
     * @param BooleanEnumType $enumType
     * @test
     * @depends I_can_get_instance
     */
    public function Its_sql_declaration_is_valid(BooleanEnumType $enumType): void
    {
        $sql = $enumType->getSQLDeclaration([], $this->getAbstractPlatform());
        self::assertSame('INTEGER', $sql);
    }

    /**
     * @param BooleanEnumType $enumType
     * @test
     * @depends I_can_get_instance
     */
    public function Its_sql_default_length_is_one(BooleanEnumType $enumType): void
    {
        $defaultLength = $enumType->getDefaultLength($this->getAbstractPlatform());
        self::assertSame(1, $defaultLength);
    }

    /**
     * @return AbstractPlatform|\Mockery\MockInterface
     */
    private function getAbstractPlatform()
    {
        return $this->mockery(AbstractPlatform::class);
    }

    /**
     * @test
     * @dataProvider provideEnumValueForDatabase
     * @param int $enumValue
     * @throws \Doctrine\DBAL\DBALException
     */
    public function Its_persisted_with_equal_value_as_enum_has(int $enumValue): void
    {
        $enum = $this->mockery(ScalarEnumInterface::class);
        $enum->expects('getValue')
            ->once()
            ->andReturn($enumValue);

        $enumType = Type::getType($this->getExpectedTypeName());
        /** @var ScalarEnumInterface $enum */
        self::assertSame($enumValue, $enumType->convertToDatabaseValue($enum, $this->getAbstractPlatform()));
    }

    public function provideEnumValueForDatabase(): array
    {
        return [
            [0],
            [1],
        ];
    }

    /**
     * @test
     * @dataProvider provideValueToConvertIntoEnum
     * @param $valueToConvert
     * @throws \Doctrine\DBAL\DBALException
     */
    public function I_get_enum_with_database_value(int $valueToConvert): void
    {
        $enumType = Type::getType($this->getExpectedTypeName());
        $enum = $enumType->convertToPHPValue($valueToConvert, $this->getAbstractPlatform());
        self::assertInstanceOf($this->getRegisteredClass(), $enum);
        self::assertSame((bool)$valueToConvert, $enum->getValue());
        self::assertSame((string)(bool)$valueToConvert, (string)$enum);
    }

    public function provideValueToConvertIntoEnum(): array
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
     * @throws \ReflectionException
     */
    public function I_got_null_instead_on_enum_if_null_is_fetched_from_database(BooleanEnumType $booleanEnumType): void
    {
        self::assertNull($booleanEnumType->convertToPHPValue(null, $this->getAbstractPlatform()));
    }

    /**
     * @param BooleanEnumType $enumType
     * @test
     * @depends I_can_get_instance
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     * @throws \ReflectionException
     */
    public function I_am_stopped_if_database_provides_invalid_value(BooleanEnumType $enumType): void
    {
        $enumType->convertToPHPValue(new \stdClass(), $this->getAbstractPlatform());
    }

    /**
     * @test
     */
    public function I_can_add_boolean_subtypes(): void
    {
        self::assertTrue(
            BooleanEnumType::addSubTypeEnum(TestSubTypeBooleanEnum::class, '~1~')
        );
        self::assertTrue(
            BooleanEnumType::hasSubTypeEnum(TestSubTypeBooleanEnum::class)
        );

        self::assertTrue(
            BooleanEnumType::addSubTypeEnum(TestAnotherSubTypeBooleanEnum::class, '~1~')
        );
        self::assertTrue(
            BooleanEnumType::hasSubTypeEnum(TestAnotherSubTypeBooleanEnum::class)
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