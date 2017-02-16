<?php
namespace Doctrineum\Tests\Boolean;

use Doctrineum\Boolean\BooleanEnum;
use Doctrineum\Scalar\ScalarEnum;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class DoctrineumBooleanExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        $reflection = new \ReflectionClass(BooleanEnum::class);

        return $reflection->getNamespaceName();
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

    /**
     * @return string
     */
    protected function getExternalRootNamespaces()
    {
        $externalRootReflection = new \ReflectionClass(ScalarEnum::class);

        return $externalRootReflection->getNamespaceName();
    }

}