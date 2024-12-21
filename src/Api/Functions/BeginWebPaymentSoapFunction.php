<?php

namespace Cws\BarclaycardSmartpayAdvance\Api\Functions;

class BeginWebPaymentSoapFunction extends SoapFunction
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'beginWebPayment';
    }

    /**
     * @return string
     */
    public function getAuditMessage(): string
    {
        return 'Making Payment';
    }
}