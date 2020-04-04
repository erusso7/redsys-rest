<?php declare(strict_types = 1);

namespace RedsysRest\Order;

use Psr\Http\Message\RequestInterface;

interface Order
{
    public function request(): RequestInterface;
}