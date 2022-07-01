<?php

namespace Pantheon\TerminusRetry\Commands;

use PHPUnit\Framework\TestCase;

class AuthRetryTest extends TestCase
{
    /**
     * Data provider for testGreeter.
     *
     * Return an array of arrays, each of which contains the parameter
     * values to be used in one invocation of the testGreeter test function.
     */
    public function retryTestValues()
    {
        return [
            [ '', [], ],
            [ ' --help', [ 'help' => true ] ],
            [ ' --define="foo=bar"', [ 'define' => ["foo=bar"] ] ],
        ];
    }

    /**
     * Test our Greeter class. Each time this function is called, it will
     * be passed data from the data provider function idendified by the
     * dataProvider annotation.
     *
     * @dataProvider retryTestValues
     */
    public function testProcessOptions($expected, $options)
    {
        $retry = new AuthRetryCommand();
        $this->assertEquals($expected, $retry->processOptions($options));
    }
}
