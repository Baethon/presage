<?php

namespace Baethon\Presage\Matcher;

use PHPUnit_Framework_MockObject_Invocation;

class ParametersMatcher extends \PHPUnit_Framework_MockObject_Matcher_StatelessInvocation
{

    /**
     * @var \PHPUnit_Framework_Constraint[]
     */
    private $parameters;

    public function __construct(...$parameters)
    {
        $this->parameters = array_map([$this, 'toConstraint'], $parameters);
    }

    private function toConstraint($value): \PHPUnit_Framework_Constraint
    {
        if (false === $value instanceof \PHPUnit_Framework_Constraint) {
            $value = new \PHPUnit_Framework_Constraint_IsEqual($value);
        }

        return $value;
    }

    /**
     * Checks if the invocation $invocation matches the current rules. If it does
     * the matcher will get the invoked() method called which should check if an
     * expectation is met.
     *
     * @param  PHPUnit_Framework_MockObject_Invocation $invocation
     *                                                             Object containing information on a mocked or stubbed method which
     *                                                             was invoked.
     * @return bool
     */
    public function matches(PHPUnit_Framework_MockObject_Invocation $invocation)
    {

        if (count($this->parameters) != count($invocation->parameters)) {
            return false;
        }

        foreach ($this->parameters as $i => $parameter) {

            try {
                $parameter->evaluate($invocation->parameters[$i]);
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        $text = 'with parameter';

        foreach ($this->parameters as $index => $parameter) {
            if ($index > 0) {
                $text .= ' and';
            }

            $text .= ' ' . $index . ' ' . $parameter->toString();
        }

        return $text;
    }
}
