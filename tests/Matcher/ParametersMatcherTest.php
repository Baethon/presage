<?php

namespace tests\Matcher;

use Baethon\Presage\Matcher\ParametersMatcher;

class ParametersMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @dataProvider parameters_provider
     */
    public function it_matches_parameters(array $parameters, array $invocationParameters, bool $expected)
    {
        $matcher = new ParametersMatcher(...$parameters);
        $invocation = $this->getMockBuilder(\PHPUnit_Framework_MockObject_Invocation_Object::class)
            ->disableOriginalConstructor()
            ->getMock();

        $invocation->parameters = $invocationParameters;

        $this->assertEquals($expected, $matcher->matches($invocation));
    }

    public function parameters_provider()
    {
        return [
            [
                ['foo', 1],
                ['foo', 1],
                true,
            ],
            [
                ['foo', 1],
                ['foo', 2],
                false,
            ],
            [
                [],
                [],
                true,
            ],
            [
                ['foo', 1],
                ['foo'],
                false,
            ],
        ];
    }
}
