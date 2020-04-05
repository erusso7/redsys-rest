<?php declare(strict_types = 1);

namespace Tests\Unit;

use Mockery;
use RedsysRest\Common\Currency;
use RedsysRest\Common\Params;
use RedsysRest\Configurator;
use RedsysRest\Encrypter;
use RedsysRest\RequestBuilder;

class RequestBuilderTest extends TestCase
{
    private $orderParams;
    private $config;
    private $expectedContent;
    private $expectedSignature;
    private $requestResult;

    public function testItShouldCreateAValidRequest()
    {
        $this->givenAValidOrder();
        $this->andADefaultConfig();

        $this->whenBuildingTheRequest();

        $this->thenTheRequestShouldBeBuilt();
    }

    private function givenAValidOrder()
    {
        $this->orderParams = [Params::PARAM_ORDER => 'some-order'];
    }

    private function andADefaultConfig()
    {
        $this->config = new Configurator(
            'some-secret',
            Currency::eur(),
            'merchant-code',
            'terminal-code'
        );
    }

    private function whenBuildingTheRequest(): void
    {
        $this->expectedContent = 'some-base64-encoded-params';
        $this->expectedSignature = 'some-signature';

        $encrypter = Mockery::mock(Encrypter::class);
        $encrypter
            ->shouldReceive('encodeParameters')
            ->andReturn($this->expectedContent);
        $encrypter
            ->shouldReceive('encrypt3DES')
            ->andReturn('encoded-key');
        $encrypter
            ->shouldReceive('signContent')
            ->andReturn($this->expectedSignature);

        $sut = new RequestBuilder($encrypter);
        $this->requestResult = $sut->build($this->config, $this->orderParams);
    }

    private function thenTheRequestShouldBeBuilt(): void
    {
        $expectedParams = [
            'Ds_MerchantParameters' => $this->expectedContent,
            'Ds_Signature' => $this->expectedSignature,
            'Ds_SignatureVersion' => 'HMAC_SHA256_V1',
        ];

        $this->assertEquals('POST', $this->requestResult->getMethod());
        $this->assertEquals($this->config->url(), $this->requestResult->getUri());
        $this->assertEquals(['application/json'], $this->requestResult->getHeader('Content-Type'));
        $this->assertEquals($expectedParams, json_decode($this->requestResult->getBody()->getContents(), true));
    }
}
