<?php declare(strict_types = 1);

namespace RedsysRest\Exceptions;

use RedsysRest\Configurator;
use RuntimeException;

class UnconfiguredClient extends RuntimeException
{
    private const MESSAGE = 'You must use an object of type ' . Configurator::class . ' to configure the client.';

    public static function create()
    {
        return new self(self::MESSAGE);
    }
}
