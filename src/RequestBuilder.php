<?php declare(strict_types = 1);

namespace RedsysRest;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use RedsysRest\Common\Params;

class RequestBuilder
{
    private const SIGNATURE_ALGORITHM = 'HMAC_SHA256_V1';

    public function build(Config $config, array $orderParams): RequestInterface
    {
        $content = $this->encodeParameters($orderParams);

        $key = $this->encrypt3DES($orderParams[Params::PARAM_ORDER], $config->secret());
        $signature = $this->signContent($content, $key);

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

    public function encodeParameters(array $orderParams): string
    {
        return base64_encode(json_encode($orderParams));
    }

    public function encrypt3DES($order, $key)
    {
        $key = base64_decode($key);
        $l = (int)ceil(strlen($order) / 8) * 8;

        return substr(
            openssl_encrypt(
                $order . str_repeat("\0", $l - strlen($order)),
                'des-ede3-cbc',
                $key,
                OPENSSL_RAW_DATA,
                "\0\0\0\0\0\0\0\0"
            ),
            0,
            $l
        );
    }

    public function signContent(string $content, string $key): string
    {
        return base64_encode(
            hash_hmac(
                'sha256',
                $content,
                $key,
                true
            )
        );
    }
}
