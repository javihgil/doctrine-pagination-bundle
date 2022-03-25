<?php
namespace Jhg\DoctrinePaginationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JhgPaginationBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
} 