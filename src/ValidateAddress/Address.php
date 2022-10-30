<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidateAddress;

use Vzikmund\SmartformApi\ValidateAddress\Address\{Coordinates, RealEstateDetails};

/**
 * Nalezena adresa
 */
final class Address
{

    public const
        /** Kód adresy (např RÚIAN kód)*/
        valueCode = "CODE",
        /** Jméno ulice */
        valueStreetName = "STREET_NAME",
        /** Kód ulice */
        valueStreetCode = "STREET_CODE",
        /** Název obce */
        valueMunicipalityName = "MUNICIPALITY_NAME",
        /** Kód obce */
        valueMunicipalityCode = "MUNICIPALITY_CODE",
        /** Nazev posty */
        valuePostName = "POST_NAME",
        /** PSC */
        valueZip = "ZIP",
        /** Obec a (pokud je to potřeba tak i) okres */
        valueMunicipalityAndOptionalDistrict = "MUNICIPALITY_AND_OPTIONAL_DISTRICT",
        /** Jméno části obce */
        valueMunicipalityPartName = "MUNICIPALITY_PART_NAME",
        /** Kód části obce */
        valueMunicipalityPartCode = "MUNICIPALITY_PART_CODE",
        /** Název městské části – např. "Praha 13" */
        valueCityArea1Name = "CITY_AREA_1_NAME",
        /** Kód městské části v RUIAN */
        valueCityArea1Code = "CITY_AREA_1_CODE",
        /** Pražský obvod – jen pro Prahu ("Praha 1" – "Praha 10) */
        valueCityArea2Name = "CITY_AREA_2_NAME",
        /** Kód pražskeho obvodu v RUIAN */
        valueCityArea2Code = "CITY_AREA_2_CODE",
        /** Správní obvod – v Praze je "Praha 1" – "Praha 22" */
        valueCityArea3Name = "CITY_AREA_3_NAME",
        /** Kód správního obvodu v RUIAN */
        valueCityArea3Code = "CITY_AREA_3_CODE",
        /** Název volebního okrsku. V Česku číslo volebního okrsku (unikátní v rámci obce) */
        valueElectoralAreaName = "ELECTORAL_AREA_NAME",
        /** Kód volebního okrsku */
        valueElectoralAreaCode = "ELECTORAL_AREA_CODE",
        /** Okres – např. "Hlavní město Praha" nebo "Liberec */
        valueDistrictName = "DISTRICT_NAME",
        /** Kód okresu */
        valueDistrictCode = "DISTRICT_CODE",
        /** Kraj – např. "Hlavní město Praha" nebo "Liberecký kraj" */
        valueRegionName = "REGION_NAME",
        /** Kôd kraje */
        valueRegionCode = "REGION_CODE",
        /** Název státu */
        valueCountryName = "COUNTRY_NAME",
        /** Dvoupísmenný ISO kód státu */
        valueCountryCode = "COUNTRY_CODE",
        /** Číslo popisné */
        valueNumberPopisne = "NUMBER_POPISNE",
        /** Číslo evidenční */
        valueNumberEvidencni = "NUMBER_EVIDENCNI",
        /** Číslo orientační */
        valueNumberOrient = "NUMBER_ORIENT",
        /** Písmeno u orientačního čísla */
        valueCharOrient = "CHAR_ORIENT",
        /** Zformátované celé číslo – např. "154", "622/37" nebo i "č.p. 35" */
        valueNumberWhole = "NUMBER_WHOLE",
        /** Zformátované celé číslo – např. "154", "622/37" nebo i "č.p. 35" */
        valueFirstLine = "FIRST_LINE",
        /** První řádka v adrese bez čísla (ulice nebo část obce) */
        valueFirstLineNoNumber = "FIRST_LINE_NO_NUMBER",
        /** Druhá řádka v adrese – např. "Plzeň 1" nebo "Praha 4 - Nusle" */
        valueSecondLine = "SECOND_LINE",
        /** Celé jméno nalezené adresy */
        valueWholeName = "WHOLE_NAME";


    /** @var array */
    private $content;

    /** @var Coordinates|null */
    private $coordinates;

    /** @var RealEstateDetails|null */
    private $realEstateDetails;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * Typ adresy
     * @return string
     */
    public function getType() : string
    {
        return $this->content[ "type" ];
    }

    /**
     * Přesna adresa
     * @return bool
     */
    public function isExact() : bool
    {
        return $this->getType() === "EXACT";
    }

    /**
     * Castecna adresa
     * @return bool
     */
    public function isPartial() : bool
    {
        return $this->getType() === "PARTIAL";
    }

    /**
     * Mapa výstupních políček zvalidované adresy
     * @return array
     */
    public function getValues() : array
    {
        return $this->content[ "values" ];
    }

    /**
     * Vystupni hodnota
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function getValue(string $key)
    {
        $values = $this->getValues();

        return $values[ $key ] ?? null;
    }

    /**
     * Souřadnice
     * @return \Vzikmund\SmartformApi\ValidateAddress\Address\Coordinates
     */
    public function getCoordinates() : Coordinates
    {
        if ($this->coordinates instanceof Coordinates) {
            return $this->coordinates;
        }
        $this->coordinates = new Coordinates($this->content[ "coordinates" ]);

        return $this->coordinates;
    }

    /**
     * Informace o nemovitosti — pokud nejsou známé, je hodnota null
     * @return \Vzikmund\SmartformApi\ValidateAddress\Address\RealEstateDetails|null
     */
    public function getRealEstateDetails() : ?RealEstateDetails
    {
        # informace byly jiz jednou vyzadany
        if ($this->realEstateDetails instanceof RealEstateDetails) {
            return $this->realEstateDetails;
        }

        $details = $this->content[ "realEstateDetails" ];

        # informace nebyly predany
        if (!$details) {
            return null;
        }

        $this->realEstateDetails = new RealEstateDetails($details);

        return $this->realEstateDetails;
    }


}