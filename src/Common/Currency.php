<?php declare(strict_types = 1);

namespace RedsysRest\Common;

class Currency
{
    private const EUR = '918';

    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function eur(): self
    {
        return new self(self::EUR);
    }

    public function code(): string
    {
        return $this->code;
    }
}