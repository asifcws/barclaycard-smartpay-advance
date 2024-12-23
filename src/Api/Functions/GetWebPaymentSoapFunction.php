<?php

namespace Cws\BarclaycardSmartpayAdvance\Api\Functions;

class GetWebPaymentSoapFunction extends SoapFunction
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'getWebPayment';
    }
}