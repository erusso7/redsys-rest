<?php declare(strict_types = 1);

namespace Tests\Unit;

use Mockery;
use RedsysRest\Common\Currency;
use RedsysRest\Common\Params;
use RedsysRest\Configurator;
use RedsysRest\Order\Order;

class ConfiguratorTest extends TestCase
{
    const DEFAULT_MERCHANT = 'default-merchant';
    const DEFAULT_TERMINAL = 'default-terminal';
    const DEFAULT_CURRENCY = '978';

    const OVERRIDE_MERCHANT = 'override-merchant';
    const OVERRIDE_TERMINAL = 'override-merchant';
    const OVERRIDE_CURRENCY = '840';

    private $order;
    private $params;

    public function testItShouldBuildTheParametersWithDefaultValues()
    {
        $this->givenAnOrderWithoutInitialParameters();
        $this->whenBuildingTheFinalParameters();
        $this->thenTheParametersShouldBeAsDefault();
    }

    public function testItShouldBuildTheParametersOverridingValues()
    {
        $this->givenAnOrderWithInitialParameters();
        $this->whenBuildingTheFinalParameters();
        $this->thenTheParametersShouldBeOverrided();
    }

    private function givenAnOrderWithoutInitialParameters()
    {
        $this->order = Mockery::mock(Order::class);
        $this->order->shouldReceive('params')->andReturn([]);
    }

    private function givenAnOrderWithInitialParameters()
    {
        $this->order = Mockery::mock(Order::class);
        $initialParameters = [
            Params::PARAM_MERCHANT => self::OVERRIDE_MERCHANT,
            Params::PARAM_TERMINAL => self::OVERRIDE_TERMINAL,
            Params::PARAM_CURRENCY => Currency::usd()->code(),
        ];
        $this->order->shouldReceive('params')->andReturn($initialParameters);
    }

    private function whenBuildingTheFinalParameters()
    {
        $sut = new Configurator(
            'some-secret',
            Currency::eur()->code(),
            self::DEFAULT_MERCHANT,
            self::DEFAULT_TERMINAL
        );
        $this->params = $sut->buildParamsFor($this->order);
    }

    private function thenTheParametersShouldBeAsDefault()
    {
        $expectedParameters = [
            Params::PARAM_MERCHANT => self::DEFAULT_MERCHANT,
            Params::PARAM_TERMINAL => self::DEFAULT_TERMINAL,
            Params::PARAM_CURRENCY => self::DEFAULT_CURRENCY,
        ];

        $this->assertEquals($expectedParameters, $this->params);
    }

    private function thenTheParametersShouldBeOverrided()
    {
        $expectedParameters = [
            Params::PARAM_MERCHANT => self::OVERRIDE_MERCHANT,
            Params::PARAM_TERMINAL => self::OVERRIDE_TERMINAL,
            Params::PARAM_CURRENCY => self::OVERRIDE_CURRENCY,
        ];

        $this->assertEquals($expectedParameters, $this->params);
    }
}