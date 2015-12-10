<?php
namespace Doctrineum\Tests\Boolean;

use Doctrineum\Boolean\BooleanEnum;
use Granam\Exceptions\Tests\Tools\AbstractTestOfExceptionsHierarchy;

class ExceptionsHierarchyTest extends AbstractTestOfExceptionsHierarchy
{
    protected function getTestedNamespace()
    {
        $reflection = new \ReflectionClass(BooleanEnum::class);

        return $reflection->getNamespaceName();
    }

    protected function getRootNamespace()
    {
        return $this->getTestedNamespace();
    }

}
