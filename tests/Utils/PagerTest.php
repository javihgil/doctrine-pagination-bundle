<?php

namespace Jhg\DoctrinePaginationBundle\Tests\Utils;

use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Jhg\DoctrinePaginationBundle\Utils\Pager;
use PHPUnit\Framework\TestCase;

class PagerTest extends TestCase
{
    public function pagesCasesProvider(): array
    {
        return [
            // with minimum maxElements, and less or equal total elements
            ['total' => 1, 'current' => 1, 'maxElements' => 5, 'expected' => [1]],
            ['total' => 2, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2]],
            ['total' => 3, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3]],
            ['total' => 4, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,4]],
            ['total' => 5, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,4,5]],
            // with minimum maxElements, and more total elements
            ['total' => 6, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 6, 'current' => 2, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 6, 'current' => 3, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 6, 'current' => 4, 'maxElements' => 5, 'expected' => [null,3,4,5,6]],
            ['total' => 6, 'current' => 5, 'maxElements' => 5, 'expected' => [null,3,4,5,6]],
            ['total' => 6, 'current' => 6, 'maxElements' => 5, 'expected' => [null,3,4,5,6]],
            // with minimum maxElements, and more total elements
            ['total' => 7, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 7, 'current' => 2, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 7, 'current' => 3, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 7, 'current' => 4, 'maxElements' => 5, 'expected' => [null,3,4,5,null]],
            ['total' => 7, 'current' => 5, 'maxElements' => 5, 'expected' => [null,4,5,6,7]],
            ['total' => 7, 'current' => 6, 'maxElements' => 5, 'expected' => [null,4,5,6,7]],
            ['total' => 7, 'current' => 7, 'maxElements' => 5, 'expected' => [null,4,5,6,7]],
            // with minimum maxElements, and more total elements
            ['total' => 8, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 8, 'current' => 2, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 8, 'current' => 3, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 8, 'current' => 4, 'maxElements' => 5, 'expected' => [null,3,4,5,null]],
            ['total' => 8, 'current' => 5, 'maxElements' => 5, 'expected' => [null,4,5,6,null]],
            ['total' => 8, 'current' => 6, 'maxElements' => 5, 'expected' => [null,5,6,7,8]],
            ['total' => 8, 'current' => 7, 'maxElements' => 5, 'expected' => [null,5,6,7,8]],
            ['total' => 8, 'current' => 8, 'maxElements' => 5, 'expected' => [null,5,6,7,8]],
            // with minimum maxElements, and more total elements
            ['total' => 10, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 10, 'current' => 2, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 10, 'current' => 3, 'maxElements' => 5, 'expected' => [1,2,3,4,null]],
            ['total' => 10, 'current' => 4, 'maxElements' => 5, 'expected' => [null,3,4,5,null]],
            ['total' => 10, 'current' => 5, 'maxElements' => 5, 'expected' => [null,4,5,6,null]],
            ['total' => 10, 'current' => 6, 'maxElements' => 5, 'expected' => [null,5,6,7,null]],
            ['total' => 10, 'current' => 7, 'maxElements' => 5, 'expected' => [null,6,7,8,null]],
            ['total' => 10, 'current' => 8, 'maxElements' => 5, 'expected' => [null,7,8,9,10]],
            ['total' => 10, 'current' => 9, 'maxElements' => 5, 'expected' => [null,7,8,9,10]],
            ['total' => 10, 'current' => 10, 'maxElements' => 5, 'expected' => [null,7,8,9,10]],
            // with odd maxElements, and more total elements
            ['total' => 10, 'current' => 1, 'maxElements' => 6, 'expected' => [1,2,3,4,5,null]],
            ['total' => 10, 'current' => 2, 'maxElements' => 6, 'expected' => [1,2,3,4,5,null]],
            ['total' => 10, 'current' => 3, 'maxElements' => 6, 'expected' => [1,2,3,4,5,null]],
            ['total' => 10, 'current' => 4, 'maxElements' => 6, 'expected' => [null,3,4,5,6,null]],
            ['total' => 10, 'current' => 5, 'maxElements' => 6, 'expected' => [null,4,5,6,7,null]],
            ['total' => 10, 'current' => 6, 'maxElements' => 6, 'expected' => [null,5,6,7,8,null]],
            ['total' => 10, 'current' => 7, 'maxElements' => 6, 'expected' => [null,6,7,8,9,10]],
            ['total' => 10, 'current' => 8, 'maxElements' => 6, 'expected' => [null,6,7,8,9,10]],
            ['total' => 10, 'current' => 9, 'maxElements' => 6, 'expected' => [null,6,7,8,9,10]],
            ['total' => 10, 'current' => 10, 'maxElements' => 6, 'expected' => [null,6,7,8,9,10]],
            // with big maxElements, and more total elements
            ['total' => 20, 'current' => 1, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,9,null]],
            ['total' => 20, 'current' => 2, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,9,null]],
            ['total' => 20, 'current' => 3, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,9,null]],
            ['total' => 20, 'current' => 4, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,9,null]],
            ['total' => 20, 'current' => 5, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,9,null]],
            ['total' => 20, 'current' => 6, 'maxElements' => 10, 'expected' => [null,3,4,5,6,7,8,9,10,null]],
            ['total' => 20, 'current' => 7, 'maxElements' => 10, 'expected' => [null,4,5,6,7,8,9,10,11,null]],
            ['total' => 20, 'current' => 8, 'maxElements' => 10, 'expected' => [null,5,6,7,8,9,10,11,12,null]],
            ['total' => 20, 'current' => 9, 'maxElements' => 10, 'expected' => [null,6,7,8,9,10,11,12,13,null]],
            ['total' => 20, 'current' => 10, 'maxElements' => 10, 'expected' => [null,7,8,9,10,11,12,13,14,null]],
            ['total' => 20, 'current' => 11, 'maxElements' => 10, 'expected' => [null,8,9,10,11,12,13,14,15,null]],
            ['total' => 20, 'current' => 12, 'maxElements' => 10, 'expected' => [null,9,10,11,12,13,14,15,16,null]],
            ['total' => 20, 'current' => 13, 'maxElements' => 10, 'expected' => [null,10,11,12,13,14,15,16,17,null]],
            ['total' => 20, 'current' => 14, 'maxElements' => 10, 'expected' => [null,11,12,13,14,15,16,17,18,null]],
            ['total' => 20, 'current' => 15, 'maxElements' => 10, 'expected' => [null,12,13,14,15,16,17,18,19,20]],
            ['total' => 20, 'current' => 16, 'maxElements' => 10, 'expected' => [null,12,13,14,15,16,17,18,19,20]],
            ['total' => 20, 'current' => 17, 'maxElements' => 10, 'expected' => [null,12,13,14,15,16,17,18,19,20]],
            ['total' => 20, 'current' => 18, 'maxElements' => 10, 'expected' => [null,12,13,14,15,16,17,18,19,20]],
            ['total' => 20, 'current' => 19, 'maxElements' => 10, 'expected' => [null,12,13,14,15,16,17,18,19,20]],
            ['total' => 20, 'current' => 20, 'maxElements' => 10, 'expected' => [null,12,13,14,15,16,17,18,19,20]],


            // with minimum maxElements, and less or equal total elements
            ['total' => 1, 'current' => 1, 'maxElements' => 5, 'expected' => [1], 'includeFirstAndLast' => true],
            ['total' => 2, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2], 'includeFirstAndLast' => true],
            ['total' => 3, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3], 'includeFirstAndLast' => true],
            ['total' => 4, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,4], 'includeFirstAndLast' => true],
            ['total' => 5, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,4,5], 'includeFirstAndLast' => true],
            // with minimum maxElements, and more total elements
            ['total' => 6, 'current' => 1, 'maxElements' => 5, 'expected' => [1,2,3,null,6], 'includeFirstAndLast' => true],
            ['total' => 6, 'current' => 2, 'maxElements' => 5, 'expected' => [1,2,3,null,6], 'includeFirstAndLast' => true],
            ['total' => 6, 'current' => 3, 'maxElements' => 5, 'expected' => [1,2,3,null,6], 'includeFirstAndLast' => true],
            ['total' => 6, 'current' => 4, 'maxElements' => 5, 'expected' => [1,null,4,5,6], 'includeFirstAndLast' => true],
            ['total' => 6, 'current' => 5, 'maxElements' => 5, 'expected' => [1,null,4,5,6], 'includeFirstAndLast' => true],
            ['total' => 6, 'current' => 6, 'maxElements' => 5, 'expected' => [1,null,4,5,6], 'includeFirstAndLast' => true],
            // with big maxElements, and more total elements
            ['total' => 20, 'current' => 1, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 2, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 3, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 4, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 5, 'maxElements' => 10, 'expected' => [1,2,3,4,5,6,7,8,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 6, 'maxElements' => 10, 'expected' => [1,null,4,5,6,7,8,9,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 7, 'maxElements' => 10, 'expected' => [1,null,5,6,7,8,9,10,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 8, 'maxElements' => 10, 'expected' => [1,null,6,7,8,9,10,11,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 9, 'maxElements' => 10, 'expected' => [1,null,7,8,9,10,11,12,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 10, 'maxElements' => 10, 'expected' => [1,null,8,9,10,11,12,13,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 11, 'maxElements' => 10, 'expected' => [1,null,9,10,11,12,13,14,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 12, 'maxElements' => 10, 'expected' => [1,null,10,11,12,13,14,15,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 13, 'maxElements' => 10, 'expected' => [1,null,11,12,13,14,15,16,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 14, 'maxElements' => 10, 'expected' => [1,null,12,13,14,15,16,17,null,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 15, 'maxElements' => 10, 'expected' => [1,null,13,14,15,16,17,18,19,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 16, 'maxElements' => 10, 'expected' => [1,null,13,14,15,16,17,18,19,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 17, 'maxElements' => 10, 'expected' => [1,null,13,14,15,16,17,18,19,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 18, 'maxElements' => 10, 'expected' => [1,null,13,14,15,16,17,18,19,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 19, 'maxElements' => 10, 'expected' => [1,null,13,14,15,16,17,18,19,20], 'includeFirstAndLast' => true],
            ['total' => 20, 'current' => 20, 'maxElements' => 10, 'expected' => [1,null,13,14,15,16,17,18,19,20], 'includeFirstAndLast' => true],
        ];
    }

    /**
     * @dataProvider pagesCasesProvider
     */
    public function testPages(int $total, int $current, int $maxElements, array $expected, bool $includeFirstAndLast = false): void
    {
        $collection = new PaginatedArrayCollection(range(1, $total), $current, 1, $total);
        $result = Pager::collapse($collection, $maxElements, $includeFirstAndLast);

        $debug = false;
        if ($debug) {
            $expectedDebug = $expected;
            if (($e = array_search($current, $expectedDebug)) !== false) {
                $expectedDebug[$e] = "$current";
            }
            $expectedDebug = json_encode($expectedDebug);

            $resultDebug = $result;
            if (($r = array_search($current, $resultDebug)) !== false) {
                $resultDebug[$r] = "$current";
            }
            $resultDebug = json_encode($resultDebug);

            echo sprintf(" total:%u current:%u, maxElements:%u expected:%s result:%s %s\n", $total, $current, $maxElements, $expectedDebug, $resultDebug, $expected == $result ? '' : '  <-- KO');
        }

        $this->assertEquals($expected, $result);
    }
}
