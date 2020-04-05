<?php declare(strict_types = 1);

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery;
use RedsysRest\Common\Currency;
use RedsysRest\Common\Params;
use RedsysRest\Config;
use RedsysRest\Exceptions\RedsysError;
use RedsysRest\Exceptions\UnconfiguredClient;
use RedsysRest\Order\Order;
use RedsysRest\Redsys;
use RedsysRest\RequestBuilder;

class RedsysTest extends TestCase
{
    private const SAMPLE_KEY = 'secret-key-from-redsys';
    private const SAMPLE_MERCHANT = '1234567890';
    private const SAMPLE_TERMINAL = '001';

    public function testItShouldCreateTheInstanceWithConfig()
    {
        $initialSut = new Redsys(new Client, new RequestBuilder);
        $configuredSut = $initialSut->withConfig($this->defaultConfig());

        $this->assertNotSame($initialSut, $configuredSut);
        $this->assertNotNull($configuredSut->config());
    }

    public function testItShouldThrowErrorIfClientIsNotConfigured()
    {
        $sut = new Redsys(new Client, new RequestBuilder);

        $this->expectException(UnconfiguredClient::class);

        $order = Mockery::mock(Order::class);
        $sut->execute($order);
    }

    public function testItShouldExecuteTheRequestedOrder()
    {
        $client = Mockery::mock(ClientInterface::class);
        $client->shouldReceive('send')->once()->andReturn(new Response);

        $params = [Params::PARAM_ORDER => '000000000001',];
        $order = Mockery::mock(Order::class);
        $order->shouldReceive('params')->andReturn($params);

        $sut = new Redsys($client, new RequestBuilder, $this->defaultConfig());
        $sut->execute($order);
    }

    public function testItShouldThrowRedsysException()
    {
        $response = new Response(200, [], '{"errorCode": "SIS0057"}');
        $client = Mockery::mock(ClientInterface::class);
        $client->shouldReceive('send')->once()->andReturn($response);

        $params = [Params::PARAM_ORDER => '000000000001',];
        $order = Mockery::mock(Order::class);
        $order->shouldReceive('params')->andReturn($params);

        $this->expectException(RedsysError::class);
        $this->expectExceptionMessage('SIS0057 - El importe a devolver supera el permitido.');

        $sut = new Redsys($client, new RequestBuilder, $this->defaultConfig());
        $sut->execute($order);
    }

    private function defaultConfig(): Config
    {
        return new Config(
            self::SAMPLE_KEY,
            Currency::eur(),
            self::SAMPLE_MERCHANT,
            self::SAMPLE_TERMINAL
        );
    }
}
