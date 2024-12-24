<?php

namespace Cws\BarclaycardSmartpayAdvance\Mappers;

use Cws\BarclaycardSmartpayAdvance\Contracts\MapperService;

class Mapper implements MapperService
{
    /**
     * @param array $request
     * @return array
     */
    public function map(array $request): array
    {
        return [];
    }

    /**
     * @param array $response
     * @return array
     */
    public function parse(array $response): array
    {
        return [];
    }
}