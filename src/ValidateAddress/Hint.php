<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidateAddress;

final class Hint
{

    /** @var array */
    private $values;

    /** @var null|Address[] */
    private $addresses = null;

    public function __construct(array $values){
        $this->values = $values;
        $addresses = $this->values["addresses"];
        if(is_array($addresses)){
            foreach ($addresses as $address){
                $this->addresses[] = new Address($address);
            }
        }
    }

    /**
     * Textová zpráva, která dovysvětluje výsledek validace
     * @return string
     */
    public function getMessage():string{
        return $this->values["message"];
    }

    /**
     * Návrh potenciálně zamýšlené adresy.
     * @return Address[]|null
     */
    public function getAddresses():?array{
        return $this->addresses;
    }

}