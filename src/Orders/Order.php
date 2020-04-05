<?php declare(strict_types = 1);

namespace RedsysRest\Orders;

interface Order
{
    public function number(): string;

    public function params(): array;
}
