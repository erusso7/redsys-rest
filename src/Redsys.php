<?php declare(strict_types = 1);

namespace RedsysRest;

use GuzzleHttp\ClientInterface;
use RedsysRest\Exceptions\UnconfiguredClient;
use RedsysRest\Order\Order;

class Redsys
{
    private ClientInterface $client;
    private ?Config $config;

    public function __construct(ClientInterface $client, ?Config $config = null)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function withConfig(Config $config): self
    {
        return new self($this->client, $config);
    }

    public function config(): Config
    {
        return $this->config;
    }

    public function execute(Order $operation): void
    {
        if ($this->config === null) {
            throw UnconfiguredClient::create();
        }

        $this->client->send($operation->request());
    }
}