<?php

namespace Cws\BarclaycardSmartpayAdvance\Api\Functions;

abstract class SoapFunction
{
    /**
     * @var array
     */
    protected array $arguments;

    /**
     * @var array
     */
    protected array $auditTags;

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments, array $auditTags)
    {
        $this->arguments = $arguments;
        $this->auditTags = $auditTags;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getAuditTags(): array
    {
        return $this->auditTags;
    }

    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return string
     */
    abstract public function getAuditMessage(): string;
}