<?php

namespace Cws\BarclaycardSmartpayAdvance\Mappers\BeginWebPayments;

use Cws\BarclaycardSmartpayAdvance\Contracts\MapperService;
use Illuminate\Support\Arr;

class BeginWebPaymentsResponseMapper implements MapperService
{

    /**
     * @param array $request
     * @return array
     */
    public function map(array $request): array
    {
        return [];
    }

    public function parse(array $response): array
    {
        $response = Arr::get($response, 'return');

        $return = [
            'redirect_url' => $this->parseRedirectUrl($response),
            'card_response' => $this->parseCardResponse($response),
            'status' => $this->parseStatus($response),
            'transaction_reference' => $this->parseTransactionReference($response),
            'store_id' => $this->parseStoreId($response),
        ];

        return $return;
    }

    /**
     * @param array $response
     * @return string
     */
    protected function parseRedirectUrl(array $response): string
    {
        $redirectUrl = Arr::get($response, 'redirectURL');

        if (!$redirectUrl && Arr::get($response, 'card.storageIndicator') == 'subsequent') {
            $redirectUrl = Arr::get($response, 'storeResultPage');
        }

        return $redirectUrl;
    }

    /**
     * @param array $response
     * @return string
     */
    protected function parseCardResponse(array $response): string
    {
        return Arr::get($response, 'response');
    }

    /**
     * @param array $response
     * @return string
     */
    protected function parseStatus(array $response): string
    {
        return Arr::get($response, 'status');
    }

    /**
     * @param array $response
     * @return string
     */
    protected function parseTransactionReference(array $response): string
    {
        return Arr::get($response, 'transactionReference');
    }

    /**
     * @param array $response
     * @return string
     */
    protected function parseStoreId(array $response): string
    {
        return Arr::get($response, 'configParams.storeID');
    }
}