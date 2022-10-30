<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidateAddress\Address;

/**
 * @property-read float $jtskX  Souřadnice JTSK – osa X
 * @property-read float $jtskY  Souřadnice JTSK – osa Y
 * @property-read float $gpsLat GPS – šířka
 * @property-read float $gpsLng GPS – délka
 */
final class Coordinates
{

    /** @var array */
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * Typ souradnic
     * @return string
     */
    public function getType() : string
    {
        return $this->values[ "type" ];
    }

    public function isExact() : bool
    {
        return $this->getType() === "EXACT";
    }

    public function isCenter() : bool
    {
        return $this->getType() === "CENTER";
    }

    public function isApproximate() : bool
    {
        return $this->getType() === "APPROXIMATE";
    }

    public function __get($property)
    {
        return $this->values[ $property ] ?? null;
    }

}