<?php declare(strict_types = 1);

namespace RedsysRest;

class Encrypter
{
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