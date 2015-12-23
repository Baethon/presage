<?php

namespace Baethon\Presage\Wrapper;

class MockWrapper
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $mockBuilder;

    /**
     * @var MethodMockWrapper[]
     */
    private $methods = [];

    public function __construct(string $className, \PHPUnit_Framework_TestCase $testCase)
    {
        $this->mockBuilder = $testCase->getMockBuilder($className)
            ->disableOriginalConstructor();
    }

    public function __call($name, array $parameters): MethodMockWrapper
    {
        $wrapper = new MethodMockWrapper($name, $parameters);
        $this->methods[] = $wrapper;

        return $wrapper;
    }

    public function reveal()
    {
        $mock = $this->mockBuilder->setMethods($m = $this->getMethodsNames())
            ->getMock();

        foreach ($this->methods as $method) {
            $method->applyToMock($mock);
        }

        return $mock;
    }

    private function getMethodsNames(): array
    {
        $names = array_map(function ($method) {
            return $method->getName();
        }, $this->methods);

        return array_unique($names);
    }
}
