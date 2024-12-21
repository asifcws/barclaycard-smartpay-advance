<?php

namespace Cws\BarclaycardSmartpayAdvance\Api\Client;

use Cws\BarclaycardSmartpayAdvance\Api\Functions\SoapFunction;
use Illuminate\Support\Arr;
use SoapClient;
use Spatie\ArrayToXml\ArrayToXml;

class BarclaycardClient extends SoapClient
{
    /**
     * @var array
     */
    public $auditTags = [];

    /**
     * @var array[]|string
     */
    public $auditFiles = [];

    /**
     * BarclaycardClient constructor
     *
     * @throws \SoapFault
     */
    public function __construct()
    {
        parent::__construct(
            config('barclaycard-smartpay-advance.base_url').'?wsdl',
            [
                'location'     => config('barclaycard-smartpay-advance.base_url'),
                'soap_version' => SOAP_1_1,
                'trace'        => true,
                'exceptions'   => true,
            ]
        );
    }

    /**
     * @param SoapFunction $function
     *
     * @return array
     * @throws \Exception
     */
    public function call(SoapFunction $function): array
    {
        $arguments = $function->getArguments();
        $this->auditTags = $function->getAuditTags();
        $this->auditFiles = [];

        try {
            //$responseRaw = $this->__soapCall($function->getName(), $this->buildXml($function->getArguments()));
            $responseRaw = $this->__soapCall($function->getName(), $arguments);
            $response = $this->parseXml($responseRaw);

            if (Arr::get($response, 'return.errors')) {
                throw new \Exception(Arr::get($response, 'return.errors.message'));
            }

            return $response;

            //return $function->handle($response, $this->auditTags, $this->auditFiles);

        } catch (\SoapFault $exception) {
            $this->auditFiles['request.json'] = json_encode($arguments, JSON_PRETTY_PRINT);
            $this->auditTags['exception'] = get_class($exception);
            dd($exception->getMessage(), $exception);

            //$function->handleException($exception, $this->auditTags, $this->auditFiles);
        }
    }

    /**
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int    $version
     * @param int    $one_way
     *
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        // Do the request and time it.
        $timer = microtime(true);
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        $timer = microtime(true) - $timer;

        // Update Audit Tags/Files
        $this->auditTags['api_host'] = parse_url($location, PHP_URL_HOST);
        $this->auditTags['api_action'] = pathinfo($action, PATHINFO_BASENAME);
        $this->auditTags['api_status'] = $this->getLastResponseStatus();
        $this->auditTags['api_time'] = round($timer, 3);
        $this->auditFiles['request.xml'] = $request;
        $this->auditFiles['response.xml'] = $response;

        return $response;
    }

    /**
     * Get the HTTP status from the last response.
     *
     * @param int $default
     *
     * @return int
     */
    protected function getLastResponseStatus(int $default = 599): int
    {

        if (preg_match('#^HTTP/.+?\s(\d{3})#', (string)$this->__getLastResponseHeaders(), $matches)) {
            return (int)$matches[1];
        }

        return $default;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function buildXml(array $data): array
    {
        foreach (Arr::dot($data) as $path => $value) {
            if (preg_match('/^(.*)\.([A-Z0-9]+)\.@xml$/i', $path, $matches) && $value) {
                Arr::forget($data, $path);
                Arr::set($data, $matches[1], ArrayToXml::convert(Arr::get($data, $matches[1] . '.' . $matches[2]), $matches[2]));
            }
        }

        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    protected function parseXml($data): array
    {
        $data = json_decode(json_encode($data), true);

        if (is_string(Arr::get($data, 'return'))) {
            $xml = Arr::get($data, 'return');
            $xml = simplexml_load_string($xml);
            $xml = json_decode(json_encode($xml), true);
            $data['return'] = $xml;
        }

        return $data;
    }
}