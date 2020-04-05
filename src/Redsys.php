<?php declare(strict_types = 1);

namespace RedsysRest;

use GuzzleHttp\ClientInterface;
use RedsysRest\Common\Currency;
use RedsysRest\Common\Params;
use RedsysRest\Exceptions\RedsysError;
use RedsysRest\Exceptions\UnconfiguredClient;
use RedsysRest\Order\Order;

class Redsys
{
    private ClientInterface $client;
    private RequestBuilder $builder;
    private ?Config $config;

    public function __construct(
        ClientInterface $client,
        RequestBuilder $builder,
        ?Config $config = null
    ) {
        $this->client = $client;
        $this->builder = $builder;
        $this->config = $config;
    }

    public function withConfig(Config $config): self
    {
        return new self($this->client, $this->builder, $config);
    }

    public function config(): Config
    {
        return $this->config;
    }

    public function execute(Order $order): void
    {
        if ($this->config === null) {
            throw UnconfiguredClient::create();
        }

        $request = $this->builder->build($this->config, $this->buildParams($order));
        $response = $this->client->send($request);

        $responseBody = json_decode($response->getBody()->getContents(), true);
        if (isset($responseBody['errorCode'])) {
            throw RedsysError::create($responseBody['errorCode']);
        }
    }

    private function buildParams(Order $order)
    {
        $orderParams = $order->params();
        $defaultParams = [
            Params::PARAM_MERCHANT,
            Params::PARAM_TERMINAL,
        ];

        foreach ($defaultParams as $paramKey) {
            if ($orderParams[$paramKey] === null) {
                $orderParams[$paramKey] = $this->config->default($paramKey);
            }
        }

        if ($orderParams[Params::PARAM_CURRENCY] === null) {
            /** @var Currency $defaultCurrency */
            $defaultCurrency = $this->config->default(Params::PARAM_CURRENCY);
            $orderParams[Params::PARAM_CURRENCY] = $defaultCurrency->code();
        }

        return $orderParams;
    }
}