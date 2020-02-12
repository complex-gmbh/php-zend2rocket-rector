<?php

namespace Complex\Zend2RocketRector\Tests\Rector\Controller;

use Complex\Zend2RocketRector\Rector\Controller\ActionCamelCaseToSnakeCase;
use Iterator;
use Complex\Zend2RocketRector\Tests\AbstractRectorWithConfigTestCase;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

class ActionCamelCaseToSnakeCaseTest extends AbstractRectorTestCase
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

    protected function getRectorClass(): string
    {
        return ActionCamelCaseToSnakeCase::class;
    }
}
