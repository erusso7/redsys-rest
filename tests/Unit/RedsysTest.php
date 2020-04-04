<?php declare(strict_types = 1);

namespace Tests\Unit;

use GuzzleHttp\ClientInterface;
use Mockery;
use RedsysRest\Config;
use RedsysRest\Exceptions\UnconfiguredClient;
use RedsysRest\Order\Order;
use RedsysRest\Redsys;

class RedsysTest extends TestCase
{
    private const SAMPLE_KEY = 'secret-key-from-redsys';

    public function testItShouldCreateTheInstanceWithConfig()
    {
        $client = Mockery::mock(ClientInterface::class);
        $initialSut = new Redsys($client);
        $configuredSut = $initialSut->withConfig(new Config(self::SAMPLE_KEY));

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
        $order->shouldReceive('method')->andReturn('post');

        $params = [];
        $order->shouldReceive('jsonSerialize')->andReturn($params);

        $sut = new Redsys($client, new Config(self::SAMPLE_KEY));
        $sut->execute($order);

        $client->shouldHaveReceived('send')->once();
    }
}
