<?php declare(strict_types = 1);

namespace Tests\Unit;

use RedsysRest\Common\Params;
use RedsysRest\Encrypter;
use RedsysRest\RequestBuilder;

class EncrypterTest extends TestCase
{
    private const TEST_ORDER = '1446068581';
    private const TEST_KEY = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
    private const TEST_PARAMS = [
        Params::PARAM_AMOUNT => '145',
        Params::PARAM_ORDER => self::TEST_ORDER,
        Params::PARAM_MERCHANT => '336664842',
        Params::PARAM_CURRENCY => '978',
        Params::PARAM_TRANSACTION_TYPE => '0',
        Params::PARAM_TERMINAL => '2',
        Params::PARAM_CARD_CVV2 => '123',
        Params::PARAM_CARD_EXPIRATION_DATE => '1512',
        Params::PARAM_CARD_NUMBER => '4548816134587756',
    ];

    public function testItShouldBuildTheParameters()
    {
        $sut = new Encrypter;
        $parameters = $sut->encodeParameters(self::TEST_PARAMS);

        $expected = 'eyJEU19NRVJDSEFOVF9BTU9VTlQiOiIxNDUiLCJEU19NRVJDSEFOVF9PUkRFUiI6IjE0NDYwNjg1ODEiLCJEU19NRVJDSEFOVF9NRVJDSEFOVENPREUiOiIzMzY2NjQ4NDIiLCJEU19NRVJDSEFOVF9DVVJSRU5DWSI6Ijk3OCIsIkRTX01FUkNIQU5UX1RSQU5TQUNUSU9OVFlQRSI6IjAiLCJEU19NRVJDSEFOVF9URVJNSU5BTCI6IjIiLCJEU19NRVJDSEFOVF9DVlYyIjoiMTIzIiwiRFNfTUVSQ0hBTlRfRVhQSVJZREFURSI6IjE1MTIiLCJEU19NRVJDSEFOVF9QQU4iOiI0NTQ4ODE2MTM0NTg3NzU2In0=';
        $this->assertEquals($expected, $parameters);
    }

    public function testItShouldEncryptTheKey()
    {
        $sut = new Encrypter;
        $encryptedKey = $sut->encrypt3DES(self::TEST_ORDER, self::TEST_KEY);

        $expectedKey = base64_decode('3sr0oTnSKSFDTDDBjgQxrw==');
        $this->assertEquals($expectedKey, $encryptedKey);
    }

    public function testItShouldBuildTheSignature()
    {
        $sut = new Encrypter;
        $signature = $sut->signContent(
            $sut->encodeParameters(self::TEST_PARAMS),
            $sut->encrypt3DES(self::TEST_ORDER, self::TEST_KEY)
        );

        $expectedSignature = 'ZWGZpejdbyg3v7ZSd3JCfQOq042iRZj41XjRsIH5iIQ=';
        $this->assertEquals($expectedSignature, $signature);
    }
}
