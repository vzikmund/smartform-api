<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidateAddress;


final class Hint
{

    /** @var array */
    private $values;

    public function __construct(array $values){
        $this->values = $values;
    }

    public function getMessage():string{
        return $this->values["message"];
    }

}