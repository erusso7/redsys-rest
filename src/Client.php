<?php declare(strict_types = 1);

namespace RedsysRest;

use GuzzleHttp\ClientInterface;
use RedsysRest\Exceptions\RedsysError;
use RedsysRest\Exceptions\UnconfiguredClient;
use RedsysRest\Orders\Order;

class Client
{
    private ClientInterface $client;
    private RequestBuilder $builder;
    private ?Configurator $config;

    public function __construct(
        ClientInterface $client,
        RequestBuilder $builder,
        ?Configurator $config = null
    ) {
        $this->client = $client;
        $this->builder = $builder;
        $this->config = $config;
    }

    public function withConfig(Configurator $config): self
    {
        return new self($this->client, $this->builder, $config);
    }

    public function config(): Configurator
    {
        return $this->config;
    }

    public function execute(Order $order): void
    {
        if ($this->config === null) {
            throw UnconfiguredClient::create();
        }

        $params = $this->config->buildParamsFor($order);
        $request = $this->builder->build($this->config, $params);
        $response = $this->client->send($request);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        if (isset($responseBody['errorCode'])) {
            throw RedsysError::create($responseBody['errorCode']);
        }
    }
}