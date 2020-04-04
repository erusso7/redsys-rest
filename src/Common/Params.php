<?php declare(strict_types = 1);

namespace RedsysRest\Common;

class Params
{
    public const PARAM_AMOUNT = 'DS_MERCHANT_AMOUNT';
    public const PARAM_CURRENCY = 'DS_MERCHANT_CURRENCY';
    public const PARAM_MERCHANT = 'DS_MERCHANT_MERCHANTCODE';
    public const PARAM_ORDER = 'DS_MERCHANT_ORDER';
    public const PARAM_TERMINAL = 'DS_MERCHANT_TERMINAL';
    public const PARAM_TRANSACTION_TYPE = 'DS_MERCHANT_TRANSACTIONTYPE';

    public const PARAM_CARD_CVV2 = 'DS_MERCHANT_CVV2';
    public const PARAM_CARD_EXPIRATION_DATE = 'DS_MERCHANT_EXPIRYDATE';
    public const PARAM_CARD_NUMBER = 'DS_MERCHANT_PAN';
}