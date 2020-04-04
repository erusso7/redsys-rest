<?php declare(strict_types = 1);

namespace RedsysRest;

use GuzzleHttp\ClientInterface;

class Redsys
{
    private ClientInterface $client;
    private Config $config;

    public function __construct(ClientInterface $client, Config $config = null)
    {
        $this->client = $client;

        if ($config !== null) {
            $this->config = $config;
        }
    }

    public function withConfig(Config $config): self
    {
        return new self($this->client, $config);
    }

    public function config(): Config
    {
        return $this->config;
    }
}