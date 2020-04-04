<?php declare(strict_types = 1);

namespace Tests\Unit;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use RedsysRest\Config;
use RedsysRest\Redsys;

class RedsysTest extends TestCase
{

    public function testItShouldCreateTheInstanceWithConfig()
    {
        $client = $this->createMock(ClientInterface::class);
        $initialSut = new Redsys($client);
        $configuredSut = $initialSut->withConfig(new Config);

        $this->assertNotSame($initialSut, $configuredSut);
        $this->assertNotNull($configuredSut->config());
    }
}
