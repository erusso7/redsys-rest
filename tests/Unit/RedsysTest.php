<?php declare(strict_types = 1);

namespace Tests\Unit;

use GuzzleHttp\ClientInterface;
use Mockery;
use Psr\Http\Message\RequestInterface;
use RedsysRest\Config;
use RedsysRest\Exceptions\UnconfiguredClient;
use RedsysRest\Order\Order;
use RedsysRest\Redsys;

class RedsysTest extends TestCase
{

    public function testItShouldCreateTheInstanceWithConfig()
    {
        $client = Mockery::mock(ClientInterface::class);
        $initialSut = new Redsys($client);
        $configuredSut = $initialSut->withConfig(new Config);

        $this->assertNotSame($initialSut, $configuredSut);
        $this->assertNotNull($configuredSut->config());
    }

    public function testItShouldThrowErrorIfClientIsNotConfigured()
    {
        $client = Mockery::mock(ClientInterface::class);
        $order = Mockery::mock(Order::class);
        $sut = new Redsys($client);

        $this->expectException(UnconfiguredClient::class);

        $sut->execute($order);
    }

    public function testItShouldExecuteTheRequestedOrder()
    {
        $client = Mockery::spy(ClientInterface::class);
        $order = Mockery::mock(Order::class);

        $request = Mockery::mock(RequestInterface::class);
        $order->shouldReceive('request')->andReturn($request);

        $sut = new Redsys($client, new Config);
        $sut->execute($order);

        $client->shouldHaveReceived('send')->with($request)->once();
    }
}
