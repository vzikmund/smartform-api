<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidatePerson;


use Vzikmund\SmartformApi\BaseResponse;

/**
 * @property-read string|null $errorMessage
 */
final class Response extends BaseResponse
{

    /** @var \Vzikmund\SmartformApi\ValidatePerson\OutputField[] */
    private $outputFields;

    public function __construct(array $content)
    {
        parent::__construct($content);
        foreach ($content[ "outputFields" ] as $field) {
            $c                                = new OutputField($field);
            $this->outputFields[ $c->fieldType ] = $c;
        }
    }

    /**
     * @return \Vzikmund\SmartformApi\ValidatePerson\OutputField[]
     */
    public function getOutputFields():array{
        return $this->outputFields;
    }

    /**
     * @param string $name
     *
     * @return \Vzikmund\SmartformApi\ValidatePerson\OutputField|null
     */
    public function getOutputField(string $name):?OutputField{
        return $this->outputFields[$name] ?? null;
    }



}