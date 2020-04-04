<?php declare(strict_types = 1);

namespace RedsysRest;

class Config
{
    private const URL_TEST = 'https://sis-t.redsys.es:25443/sis/rest/trataPeticionREST';
    private const URL_LIVE = 'https://sis.redsys.es/sis/rest/trataPeticionREST';
    private const SIGNATURE_ALGORITHM = 'HMAC_SHA256_V1';

    public const ENV_TEST = 0;
    public const ENV_LIVE = 1;

    private string $secret;
    private string $url;

    public function __construct(string $secret, int $env = self::ENV_TEST)
    {
        $this->secret = $secret;
        $this->url = $env === self::ENV_TEST
            ? self::URL_TEST
            : self::URL_LIVE;
    }

    public function url(): string
    {
        return $this->url;
    }
}