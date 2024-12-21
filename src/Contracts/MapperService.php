<?php

namespace Cws\BarclaycardSmartpayAdvance\Contracts;

interface MapperService
{
    /**
     * @param array $request
     * @return array
     */
    public function map(array $request): array;

    /**
     * @param array $response
     * @return array
     */
    public function parse(array $response): array;
}