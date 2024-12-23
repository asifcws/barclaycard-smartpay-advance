<?php

namespace Cws\BarclaycardSmartpayAdvance\Api\Functions;

abstract class SoapFunction
{
    /**
     * @var array
     */
    protected array $arguments;

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return string
     */
    abstract public function getName(): string;
}