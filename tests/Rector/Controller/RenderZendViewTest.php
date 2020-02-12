<?php

namespace Complex\Zend2RocketRector\Tests\Rector\Controller;

use Complex\Zend2RocketRector\Rector\Controller\RenderZendView;
use Iterator;
use Complex\Zend2RocketRector\Tests\AbstractRectorWithConfigTestCase;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

class RenderZendViewTest extends AbstractRectorTestCase
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
        yield [__DIR__ . '/Fixture/RenderZendViewFixture.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return RenderZendView::class;
    }
}
