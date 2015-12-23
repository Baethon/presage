<?php

namespace Baethon\Presage\Wrapper;

use Baethon\Presage\Matcher\ParametersMatcher;

class MethodMockWrapper
{

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var \PHPUnit_Framework_MockObject_Stub
     */
    private $stub;

    public function __construct(string $methodName, array $parameters = [])
    {
        $this->methodName = $methodName;
        $this->parameters = $parameters;
    }

    public function willReturn($value): self
    {
        $this->stub = new \PHPUnit_Framework_MockObject_Stub_Return($value);

        return $this;
    }

    public function willThrow($exception): self
    {
        if (true === is_string($exception)) {
            $exception = new $exception;
        }

        $this->stub = new \PHPUnit_Framework_MockObject_Stub_Exception($exception);

        return $this;
    }

    public function applyToMock($mock)
    {
        $mock->expects(new ParametersMatcher(...$this->parameters))
            ->method($this->methodName)
            ->will($this->stub);
    }

    public function getName(): string
    {
        return $this->methodName;
    }
}
