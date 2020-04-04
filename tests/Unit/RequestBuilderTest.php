<?php declare(strict_types = 1);

namespace Tests\Unit;

use RedsysRest\Common\Params;
use RedsysRest\RequestBuilder;

class RequestBuilderTest extends TestCase
{
    private const TEST_ORDER = '000000000001';
    private const TEST_KEY = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
    private const TEST_PARAMS = [
        Params::PARAM_AMOUNT => 10000,
        Params::PARAM_ORDER => self::TEST_ORDER,
        Params::PARAM_MERCHANT => '999008881',
        Params::PARAM_CURRENCY => '981',
        Params::PARAM_TRANSACTION_TYPE => 0,
        Params::PARAM_TERMINAL => '001',
    ];

    public function testItShouldBuildTheParameters()
    {
        $sut = new RequestBuilder;
        $parameters = $sut->encodeParameters(self::TEST_PARAMS);

        $expected = 'eyJEU19NRVJDSEFOVF9BTU9VTlQiOjEwMDAwLCJEU19NRVJDSEFOVF9PUkRFUiI6IjAwMDAwMDAwMDAwMSIsIkRTX01FUkNIQU5UX01FUkNIQU5UQ09ERSI6Ijk5OTAwODg4MSIsIkRTX01FUkNIQU5UX0NVUlJFTkNZIjoiOTgxIiwiRFNfTUVSQ0hBTlRfVFJBTlNBQ1RJT05UWVBFIjowLCJEU19NRVJDSEFOVF9URVJNSU5BTCI6IjAwMSJ9';
        $this->assertEquals($expected, $parameters);
    }

    public function testItShouldEncryptTheKey()
    {
        $sut = new RequestBuilder;
        $encryptedKey = $sut->encrypt3DES(self::TEST_ORDER, self::TEST_KEY);

        $expectedKey = base64_decode('LxdcNgoaWfPXpZe5tuCNSA==');
        $this->assertEquals($expectedKey, $encryptedKey);
    }

    public function testItShouldBuildTheSignature()
    {
        $sut = new RequestBuilder;
        $signature = $sut->signContent(
            $sut->encodeParameters(self::TEST_PARAMS),
            $sut->encrypt3DES(self::TEST_ORDER, self::TEST_KEY)
        );

        $expectedSignature = 'fSmIwCO7c1arvQwO7bKoOOpIrZoGqkGFlbjvWuEI7cw=';
        $this->assertEquals($expectedSignature, $signature);
    }
}
