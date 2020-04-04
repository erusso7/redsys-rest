<?php declare(strict_types = 1);

namespace RedsysRest\Common;

class CreditCard
{
    private string $number;
    private string $cvv2;
    private string $expirationMonth;
    private string $expirationYear;

    public function __construct(
        string $number,
        string $cvv2,
        string $expirationMonth,
        string $expirationYear
    ) {
        $this->number = $number;
        $this->cvv2 = $cvv2;
        $this->expirationMonth = $expirationMonth;
        $this->expirationYear = $expirationYear;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function cvv2(): string
    {
        return $this->cvv2;
    }

    public function expirationMonth(): string
    {
        return $this->expirationMonth;
    }

    public function expirationYear(): string
    {
        return $this->expirationYear;
    }
}