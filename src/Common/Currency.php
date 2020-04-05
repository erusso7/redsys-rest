<?php declare(strict_types = 1);

namespace RedsysRest\Common;

class Currency
{
    private const EUR = '978';
    private const USD = '840';

    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function eur(): self
    {
        return new self(self::EUR);
    }

    public static function usd(): self
    {
        return new self(self::USD);
    }

    public function code(): string
    {
        return $this->code;
    }
}
