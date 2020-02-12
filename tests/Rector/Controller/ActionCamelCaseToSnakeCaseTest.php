<?php

namespace Complex\Zend2RocketRector\Tests\Rector\Controller;

use Iterator;
use Complex\Zend2RocketRector\Tests\AbstractRectorWithConfigTestCase;

class ActionCamelCaseToSnakeCaseTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     *
     * @param string $file
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/ActionCamelCaseToSnakeCaseFixture.php.inc'];
    }
}
