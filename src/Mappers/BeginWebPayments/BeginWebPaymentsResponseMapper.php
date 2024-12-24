<?php

namespace Cws\BarclaycardSmartpayAdvance\Mappers\BeginWebPayments;

use Cws\BarclaycardSmartpayAdvance\Mappers\Common\WebPaymentResponseMapper;
use Cws\BarclaycardSmartpayAdvance\Mappers\Mapper;

class BeginWebPaymentsResponseMapper extends Mapper
{
    /**
     * @param array $response
     * @return array
     */
    public function parse(array $response): array
    {
        $mapper = app(WebPaymentResponseMapper::class);

        return $mapper->parse($response);
    }
}