<?php declare(strict_types = 1);

namespace RedsysRest\Order;

interface Order
{
    public function number(): string;

    public function method(): string;

    public function params(): array;
}