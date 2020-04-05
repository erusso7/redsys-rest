<?php declare(strict_types = 1);

namespace RedsysRest\Order;

use RedsysRest\Common\Currency;
use RedsysRest\Common\Params;

class Refund implements Order
{
    private const TYPE = 3;
    private string $amount;
    private string $number;
    private ?Currency $currency;
    private ?string $merchant;
    private ?string $terminal;

    public function __construct(
        string $amount,
        string $number,
        Currency $currency = null,
        string $merchant = null,
        string $terminal = null
    ) {
        $this->amount = $amount;
        $this->number = $number;
        $this->currency = $currency;
        $this->merchant = $merchant;
        $this->terminal = $terminal;
    }

    public function params(): array
    {
        return [
            Params::PARAM_AMOUNT => $this->amount,
            Params::PARAM_ORDER => $this->number,
            Params::PARAM_CURRENCY => isset($this->currency) ? $this->currency->code() : null,
            Params::PARAM_MERCHANT => $this->merchant,
            Params::PARAM_TERMINAL => $this->terminal,
            Params::PARAM_TRANSACTION_TYPE => self::TYPE,
        ];
    }

    public function number(): string
    {
        return $this->number;
    }
}