<?php declare(strict_types = 1);

namespace Tests\Unit\Order;

use RedsysRest\Common\Currency;
use RedsysRest\Common\Params;
use RedsysRest\Order\Refund;
use Tests\Unit\TestCase;

class RefundTest extends TestCase
{
    private const AMOUNT = '10000';
    private const NUMBER = '000000000001';
    private const MERCHANT = 'merchant-code';
    private const TERMINAL = '10';

    public function testItShouldBuildTheMinimumRequiredParameters()
    {
        $refund = new Refund(self::AMOUNT, self::NUMBER);

        $expectedParameters = [
            Params::PARAM_AMOUNT => self::AMOUNT,
            Params::PARAM_ORDER => $refund->number(),
            Params::PARAM_CURRENCY => null,
            Params::PARAM_MERCHANT => null,
            Params::PARAM_TERMINAL => null,
            Params::PARAM_TRANSACTION_TYPE => 3,
        ];
        $this->assertEquals($expectedParameters, $refund->params());
    }

    public function testItShouldBuildAllTheParameters()
    {
        $refund = new Refund(
            self::AMOUNT,
            self::NUMBER,
            Currency::eur(),
            self::MERCHANT,
            self::TERMINAL,
        );

        $expectedParameters = [
            Params::PARAM_AMOUNT => self::AMOUNT,
            Params::PARAM_ORDER => $refund->number(),
            Params::PARAM_CURRENCY => Currency::eur()->code(),
            Params::PARAM_MERCHANT => self::MERCHANT,
            Params::PARAM_TERMINAL => self::TERMINAL,
            Params::PARAM_TRANSACTION_TYPE => 3,
        ];
        $this->assertEquals($expectedParameters, $refund->params());
    }
}
