<?php

namespace tests\Wrapper;

use Baethon\Presage\Wrapper\MethodMockWrapper;
use stubs\ClassWithConstructor;

class MethodMockWrapperTest extends \PHPUnit_Framework_TestCase
{

    private $mock;

    public function setUp()
    {
        $this->mock = $this->getMockBuilder(ClassWithConstructor::class)
            ->disableOriginalConstructor()
            ->setMethods(['testMethod'])
            ->getMock();
    }

    /**
     * @test
     */
    public function it_returns_name()
    {
        $method = new MethodMockWrapper('testMethod');
        $this->assertEquals('testMethod', $method->getName());
    }

    /**
     * @test
     */
    public function it_applies_return_value()
    {
        $method = new MethodMockWrapper('testMethod', ['foo']);

        $method->willReturn($returnValue = 'bar');
        $method->applyToMock($this->mock);

        $this->assertEquals($returnValue, $this->mock->testMethod('foo'));
        $this->assertNull($this->mock->testMethod('bar'));
    }

    /**
     * @test
     */
    public function it_throws_exception_from_string()
    {
        $method = new MethodMockWrapper($methodName = 'testMethod');
        $method->willThrow('LogicException');

        $method->applyToMock($this->mock);

        $this->setExpectedException('LogicException');
        $this->mock->testMethod();
    }

    /**
     * @test
     */
    public function it_throws_exception_from_instance()
    {
        $method = new MethodMockWrapper('testMethod');
        $method->willThrow(new \LogicException());

        $method->applyToMock($this->mock);

        $this->setExpectedException('LogicException');
        $this->mock->testMethod();
    }
}
