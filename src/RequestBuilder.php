<?php declare(strict_types = 1);

namespace RedsysRest;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use RedsysRest\Common\Params;

class RequestBuilder
{
    private const SIGNATURE_ALGORITHM = 'HMAC_SHA256_V1';

    private Encrypter $encrypter;

    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    public function build(Configurator $config, array $orderParams): RequestInterface
    {
        $content = $this->encrypter->encodeParameters($orderParams);

        $key = $this->encrypter->encrypt3DES($orderParams[Params::PARAM_ORDER], $config->secret());
        $signature = $this->encrypter->signContent($content, $key);

        $requestBody = json_encode([
            'Ds_MerchantParameters' => $content,
            'Ds_Signature' => $signature,
            'Ds_SignatureVersion' => self::SIGNATURE_ALGORITHM,
        ]);

        return new Request(
            'post',
            $config->url(),
            ['Content-Type' => 'application/json'],
            $requestBody
        );
    }
}
