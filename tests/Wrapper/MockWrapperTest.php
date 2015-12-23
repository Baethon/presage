<?php

namespace tests\Wrapper;

use Baethon\Presage\Wrapper\MethodMockWrapper;
use Baethon\Presage\Wrapper\MockWrapper;
use stubs\ClassWithConstructor;

class MockWrapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var MockWrapper
     */
    private $wrapper;

    public function setUp()
    {
        $this->wrapper = new MockWrapper(ClassWithConstructor::class, $this);
    }

    /**
     * @test
     */
    public function it_creates_mock_for_class_with_constructor_arguments()
    {
        $expectedMock = $this->getMockBuilder(ClassWithConstructor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals($expectedMock, $this->wrapper->reveal());
    }

    /**
     * @test
     */
    public function it_creates_method_wrapper()
    {
        $method = $this->wrapper->testMethod();
        $expectedMethod = new MethodMockWrapper('testMethod');

        $this->assertEquals($expectedMethod, $method);
    }

    /**
     * @test
     */
    public function it_creates_method_wrapper_with_arguments()
    {
        $method = $this->wrapper->testMethod('foo', 'bar');
        $expectedMethod = new MethodMockWrapper('testMethod', ['foo', 'bar']);

        $this->assertEquals($expectedMethod, $method);
    }

    /**
     * @test
     */
    public function it_applies_methods_to_mock()
    {
        $this->wrapper->testMethod()->willReturn('foo');
        $mock = $this->wrapper->reveal();

        $this->assertEquals('foo', $mock->testMethod());
    }

    /**
     * @test
     */
    public function it_returns_different_values_for_different_method_calls()
    {
        $this->wrapper->testMethod()->willReturn('foo');
        $this->wrapper->testMethod('bar')->willReturn('bar');

        $mock = $this->wrapper->reveal();

        $this->assertEquals('foo', $mock->testMethod());
        $this->assertEquals('bar', $mock->testMethod('bar'));
    }
}
