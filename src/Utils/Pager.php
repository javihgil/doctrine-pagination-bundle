<?php

namespace Jhg\DoctrinePaginationBundle\Utils;

use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;

class Pager
{
    public static function collapse(PaginatedArrayCollection $collection, int $elements = 5, bool $alwaysIncludeFirstAndLast = false): array
    {
        if ($elements < 5) {
            throw new \Exception('Min elements to show in pager is 5');
        }

        $current = (int)$collection->getPage();
        $pages = (int)$collection->getPages();
        $pagesArray = range(1, $pages);

        if ($pages <= $elements) {
            return $pagesArray;
        }

        // calculate pivot
        $start = $current - floor(($elements-1)/2);

        // fix extreme cases
        if ($start < 1) {
            $start = 1;
        }
        $end = $current + ceil(($elements-1)/2);
        if ($end > $pages) {
            $start -= $end - $pages;
        }

        // remove extra elements
        $pagesArray = array_slice($pagesArray, $start - 1, $elements);

        if ($alwaysIncludeFirstAndLast) {
            // add null at the begining
            if ($pagesArray[0] != 1) {
                array_shift($pagesArray);
                array_unshift($pagesArray, 1);
                if (isset($pagesArray[1]) && $pagesArray[1] != 2) {
                    $pagesArray[1] = null;
                }
            }
            // add null at the begining
            if ($pagesArray[$elements-1] != $pages) {
                array_pop($pagesArray);
                array_push($pagesArray, $pages);

                if (isset($pagesArray[$elements-2]) && $pagesArray[$elements-2] != $pages - 1) {
                    $pagesArray[$elements-2] = null;
                }
            }
        } else {
            // add null at the begining
            if ($pagesArray[0] != 1) {
                $pagesArray[0] = null;
            }
            // add null at the begining
            if ($pagesArray[$elements-1] != $pages) {
                $pagesArray[$elements-1] = null;
            }
        }

        return $pagesArray;
    }
}