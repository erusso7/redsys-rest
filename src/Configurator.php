<?php declare(strict_types = 1);

namespace RedsysRest;

use RedsysRest\Common\Params;
use RedsysRest\Orders\Order;

class Configurator
{
    private const URL_TEST = 'https://sis-t.redsys.es:25443/sis/rest/trataPeticionREST';
    private const URL_LIVE = 'https://sis.redsys.es/sis/rest/trataPeticionREST';

    public const ENV_TEST = 0;
    public const ENV_LIVE = 1;

    private $secret;
    private $url;
    private $defaults;

    public function __construct(
        string $secret,
        string $defaultCurrencyCode,
        string $defaultMerchant,
        string $defaultTerminal,
        int $env = self::ENV_TEST
    ) {
        $this->secret = $secret;
        $this->url = $env === self::ENV_TEST
            ? self::URL_TEST
            : self::URL_LIVE;

        $this->defaults[Params::PARAM_CURRENCY] = $defaultCurrencyCode;
        $this->defaults[Params::PARAM_MERCHANT] = $defaultMerchant;
        $this->defaults[Params::PARAM_TERMINAL] = $defaultTerminal;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function secret(): string
    {
        return $this->secret;
    }

    public function buildParamsFor(Order $order): array
    {
        $orderParams = $order->params();
        $defaultParams = [
            Params::PARAM_MERCHANT,
            Params::PARAM_TERMINAL,
            Params::PARAM_CURRENCY,
        ];

        foreach ($defaultParams as $paramKey) {
            if (!isset($orderParams[$paramKey])) {
                $orderParams[$paramKey] = $this->defaults[$paramKey];
            }
        }

        return $orderParams;
    }
}
