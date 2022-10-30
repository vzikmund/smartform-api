<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidateAddress\Address;


final class RealEstateDetails
{

    public const
        /** Kmenové číslo parcely */
        valueBuildingParcelNumber1 = "BUILDING_PARCEL_NUMBER_1",
        /** Poddělení (pořadové číslo nové parcely v rámci původní parcely). Pokud není, má hodnotu null */
        valueBuildingParcelNumber2 = "BUILDING_PARCEL_NUMBER_2",
        /** Název katastrálního území */
        valueCadastralUnitName = "CADASTRAL_UNIT_NAME",
        /** Číslo katastrálního území */
        valueCadastralUnitCode = "CADASTRAL_UNIT_CODE",
        /** Informace o výtahu. Může mít hodnoty: "true", "false", null */
        valueBuildingWithLift = "BUILDING_WITH_LIFT",
        /** Počet podlaží */
        valueNumberOfStoreys = "NUMBER_OF_STOREYS",
        /** Počet bytů */
        valueNumberOfFlats = "NUMBER_OF_FLATS",
        /** Podlahová plocha [m2] */
        valueFloorArea = "FLOOR_AREA";


    /** @var array */
    private $values;

    public function __construct(array $values){
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getValues():array{
        return $this->values;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getValue(string $key){
        return $this->values[$key] ?? null;
    }

}