<?php
namespace Doctrineum\Tests\Boolean;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrineum\Boolean\BooleanEnum;
use Doctrineum\Boolean\BooleanEnumType;
use Doctrineum\Scalar\ScalarEnumInterface;

class BooleanEnumTypeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Doctrineum\Boolean\BooleanEnumType
     */
    protected function getEnumTypeClass()
    {
        return BooleanEnumType::getClass();
    }

    /**
     * @return \Doctrineum\Boolean\BooleanEnum
     */
    protected function getRegisteredEnumClass()
    {
        return BooleanEnum::getClass();
    }

    protected function setUp()
    {
        $enumTypeClass = $this->getEnumTypeClass();
        if (!Type::hasType($enumTypeClass::getTypeName())) {
            Type::addType($enumTypeClass::getTypeName(), $enumTypeClass);
        }
    }

    protected function tearDown()
    {
        \Mockery::close();

        $enumTypeClass = $this->getEnumTypeClass();
        $enumType = Type::getType($enumTypeClass::getTypeName());
        /** @var BooleanEnumType $enumType */
        if ($enumType::hasSubTypeEnum($this->getSubTypeEnumClass())) {
            $this->assertTrue($enumType::removeSubTypeEnum($this->getSubTypeEnumClass()));
        }
    }

    /**
     * @test
     */
    public function can_be_registered()
    {
        $enumTypeClass = $this->getEnumTypeClass();
        if (!Type::hasType($enumTypeClass::getTypeName())) {
            Type::addType($enumTypeClass::getTypeName(), $enumTypeClass);
        }
        $this->assertTrue(Type::hasType($enumTypeClass::getTypeName()));
    }

    /**
     * @test
     */
    public function type_instance_can_be_obtained()
    {
        $enumTypeClass = $this->getEnumTypeClass();
        $instance = $enumTypeClass::getType($enumTypeClass::getTypeName());
        $this->assertInstanceOf($enumTypeClass, $instance);

        return $instance;
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends type_instance_can_be_obtained
     */
    public function type_name_is_as_expected(BooleanEnumType $enumType)
    {
        $enumTypeClass = $this->getEnumTypeClass();
        $typeName = $this->convertToTypeName($enumTypeClass);
        $constantName = strtoupper($typeName);
        $this->assertTrue(defined("$enumTypeClass::$constantName"));
        $this->assertSame($enumTypeClass::getTypeName(), $typeName);
        $this->assertSame($typeName, constant("$enumTypeClass::$constantName"));
        $this->assertSame($enumType::getTypeName(), $enumTypeClass::getTypeName());
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
     * @depends type_instance_can_be_obtained
     */
    public function sql_declaration_is_valid(BooleanEnumType $enumType)
    {
        $sql = $enumType->getSQLDeclaration([], $this->getAbstractPlatform());
        $this->assertSame('INTEGER', $sql);
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends type_instance_can_be_obtained
     */
    public function sql_default_length_is_one(BooleanEnumType $enumType)
    {
        $defaultLength = $enumType->getDefaultLength($this->getAbstractPlatform());
        $this->assertSame(1, $defaultLength);
    }

    /**
     * @return AbstractPlatform
     */
    private function getAbstractPlatform()
    {
        return \Mockery::mock(AbstractPlatform::class);
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends type_instance_can_be_obtained
     */
    public function enum_as_database_value_is_that_enum_value(BooleanEnumType $enumType)
    {
        $enum = \Mockery::mock(ScalarEnumInterface::class);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $enum->shouldReceive('getValue')
            ->once()
            ->andReturn($value = 1);
        /** @var ScalarEnumInterface $enum */
        $this->assertSame($value, $enumType->convertToDatabaseValue($enum, $this->getAbstractPlatform()));
    }

    /**
     * conversions to PHP value
     */

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends type_instance_can_be_obtained
     */
    public function value_is_propagated_to_enum_on_conversion_to_php_value(BooleanEnumType $enumType)
    {
        $enum = $enumType->convertToPHPValue($integer = 12345, $this->getAbstractPlatform());
        $this->assertInstanceOf($this->getRegisteredEnumClass(), $enum);
        $this->assertSame(boolval($integer), $enum->getValue());
        $this->assertSame('1', (string)$enum);
    }

    /**
     * @test
     */
    public function can_register_another_enum_type()
    {
        $anotherEnumType = $this->getAnotherEnumTypeClass();
        if (!$anotherEnumType::isRegistered()) {
            $this->assertTrue($anotherEnumType::registerSelf());
        } else {
            $this->assertFalse($anotherEnumType::registerSelf());
        }

        $this->assertTrue($anotherEnumType::isRegistered());
    }

    /**
     * @param BooleanEnumType $enumType
     *
     * @test
     * @depends type_instance_can_be_obtained
     * @expectedException \Doctrineum\Boolean\Exceptions\UnexpectedValueToConvert
     */
    public function I_am_stopped_if_use_invalid_value(BooleanEnumType $enumType)
    {
        $enumType->convertToPHPValue(new \stdClass(), $this->getAbstractPlatform());

    }

    /**
     * @return AbstractPlatform
     */
    protected function getPlatform()
    {
        return \Mockery::mock(AbstractPlatform::class);
    }

    /**
     * @return string|TestSubTypeBooleanEnum
     */
    protected function getSubTypeEnumClass()
    {
        return TestSubTypeBooleanEnum::getClass();
    }

    /**
     * @return string|TestAnotherSubTypeBooleanEnum
     */
    protected function getAnotherSubTypeEnumClass()
    {
        return TestAnotherSubTypeBooleanEnum::getClass();
    }

    /**
     * @return TestAnotherBooleanEnumType|string
     */
    protected function getAnotherEnumTypeClass()
    {
        return TestAnotherBooleanEnumType::getClass();
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
