<?php

namespace Cws\BarclaycardSmartpayAdvance\Mappers\GetWebPayment;

use Carbon\Carbon;
use Cws\BarclaycardSmartpayAdvance\Contracts\MapperService;
use Illuminate\Support\Arr;

class GetWebPaymentRequestMapper implements MapperService
{

    public function map(array $request): array
    {
        $enterpriseId = $this->mapEnterpriseId();
        $clientId = $this->mapClientId();
        $transNo = $this->mapTransNo($request);
        $environment = $this->mapEnvironment();
        $version = $this->mapVersion();
        $storeId = $this->mapStoreId($request);
        $transactionTime = $this->mapTransactionTime();
        $transactionReference = $this->mapTransReference($request);
        $authToken = $this->mapAuthToken(get_defined_vars());

        return [
            'beginWebPayment' => [
                'arg0' => [
                    'requester' => [
                        'authToken' => $authToken,
                        'enterpriseID' => $enterpriseId,
                        'storeID' => $storeId,
                        'clientID' => $clientId,
                        'transNo' => $transNo,
                        'environment' => $environment,
                        'version' => $version,
                    ],
                    'transactionTime' => $transactionTime,
                    'transactionReference' => $transactionReference,
                ]
            ]
        ];
    }

    /**
     * @param array $response
     * @return array
     */
    public function parse(array $response): array
    {
        return [];
    }

    /**
     * @return string
     */
    protected function mapEnterpriseId(): string
    {
        return config('barclaycard-smartpay-advance.enterprise_id');
    }

    /**
     * @return string
     */
    protected function mapClientId(): string
    {
        return config('barclaycard-smartpay-advance.client_id');
    }

    /**
     * @param array $request
     * @return string
     */
    protected function mapTransNo(array $request): string
    {
        return Arr::get($request, 'transaction_no');
    }

    /**
     * @param array $request
     * @return string
     */
    protected function mapTransReference(array $request): string
    {
        return Arr::get($request, 'transaction_reference');
    }

    /**
     * @param array $request
     * @return string
     */
    protected function mapStoreId(array $request): string
    {
        return Arr::get($request, 'store_id');
    }

    /**
     * @return string
     */
    protected function mapEnvironment(): string
    {
        return 'ECommerce';
    }

    protected function mapVersion(): int
    {
        return 22;
    }

    /**
     * @return string
     */
    protected function mapTransactionTime(): string
    {
        return Carbon::now('Europe/London')->format('Y-m-d\TH:i:s.001');
    }

    /**
     * @param array $request
     * @return string
     */
    protected function mapAuthToken(array $request): string
    {
        $enterpiseId = config('barclaycard-smartpay-advance.enterprise_id');
        $cliendId = config('barclaycard-smartpay-advance.client_id');
        $secret = config('barclaycard-smartpay-advance.hmac_secret');
        $transactionNo = Arr::get($request, 'transNo');
        $transactionReference = Arr::get($request, 'transactionReference');
        $timestamp = Arr::get($request, 'transactionTime');
        $signiture = "{$enterpiseId}{$cliendId}{$transactionNo}{$transactionReference}{$timestamp}";

        return  hash_hmac('sha256', $signiture, $secret);
    }
}