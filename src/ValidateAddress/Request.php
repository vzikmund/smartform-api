<?php

declare(strict_types = 1);

namespace Vzikmund\SmartformApi\ValidateAddress;

use GuzzleHttp\Client;
use Vzikmund\SmartformApi\BaseRequest;
use Vzikmund\SmartformApi\Exception\InvalidArgumentException;

final class Request extends BaseRequest
{

    /**
     * Klice vstupnich hodnot
     * @var string
     */
    public const
        /** Ulice (nebo část obce) a číslo. Např. "Smetanova 11", "Rychlonožkova 1567/4a" nebo "Horní Lhota 17"  */
        valueFirstLine = "FIRST_LINE",
        /** Celý řádek s městem. Např. "České Budějovice", "Praha", "Praha 4", "Praha - Podolí", "Praha 4 - Podolí", "Praha 4, Podolí" */
        valueSecondLine = "SECOND_LINE",
        /** Obec a (nepovinně) okres. Obec a okres mohou být spojeny libovolně (např. čárkou, závorkami, středníkem) */
        valueMunicipalityAndDistrict = "MUNICIPALITY_AND_DISTRICT",
        /** PSC */
        valueZip = "ZIP",
        /** Jméno ulice */
        valueStreet = "STREET",
        /** Kód ulice */
        valueStreetCode = "STREET_CODE",
        /** Kód části obce */
        valueMunicipalityPartCode = "MUNICIPALITY_PART_CODE",
        /** Kód obce */
        valueMunicipalityCode = "MUNICIPALITY_CODE",
        /** Celá adresa */
        valueWholeAddress = "WHOLE_ADDRESS",
        /** Celé číslo domu (popisné a/nebo orientační a/nebo orientační písmeno) */
        valueNumberWhole = "NUMBER_WHOLE",
        /** Nějaké jedno číslo domu */
        valueNumber1 = "NUMBER_1",
        /** Nějaké jedno číslo domu */
        valueNumber2 = "NUMBER_2",
        /** Nějaké jedno číslo domu */
        valueNumber3 = "NUMBER_3",
        /** Číslo popisné */
        valueNumberPopis = "NUMBER_POPIS",
        /** Číslo evidenční */
        valueNumberEvident = "NUMBER_EVIDENT",
        /** Číslo orientační */
        valueNumberOrient = "NUMBER_ORIENT",
        /** Písmeno u orientačního čísla */
        valueCharOrient = "CHAR_ORIENT",
        /** Kód adresy (např RÚIAN kód)*/
        valueCode = "CODE";

    /**
     * Identifikace dotazu – slouží jen pro spárování dotazu a odpovědi. Pro validaci není důležité.
     * @var int
     */
    private $id;

    /**
     * Vstupní hodnoty, podle kterých se adresa bude hledat.
     * @var array
     */
    private $values = [];

    /**
     * Seznam kódů států, ve kterých se bude adresa hledat. Kódy mohou nabývat hodnot "CZ" a "SK".
     * @var array
     */
    private $countries = [];

    /**
     * Konfigurace – umožňuje měnit standardní chování API. Můžete ponechat prázdné.
     * @var array
     */
    private $configuration = [];

    public function __construct(int $id, Client $client, array $logHandlers)
    {
        $this->id = $id;
        parent::__construct($client, $logHandlers);
    }

    /**
     * Validace adresy
     *
     * @return \Vzikmund\SmartformApi\ValidateAddress\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Vzikmund\SmartformApi\Exception\SmartApiException
     */
    public function validate() : Response
    {
        $data = [
            "id"        => $this->id,
            "values"    => $this->getValues(),
            "countries" => $this->getCountries(),
        ];

        if (count($this->getConfiguration()) > 0) {
            $data[ "configuration" ] = $this->getConfiguration();
        }

        $response = $this->call("validateAddress/v9", "POST", $data);

        return new Response($response);
    }


    /**
     * Přidání vstupní hodnoty
     *
     * @param string $type
     * @param string $value
     *
     * @return $this
     * @throws \Vzikmund\SmartformApi\Exception\InvalidArgumentException
     */
    public function addValue(string $type, string $value) : self
    {
        $constants = $this->getConstants();
        if (!in_array($type, $constants)) {
            throw new InvalidArgumentException("Unknown value key '{$type}'");
        }
        $this->values[ $type ] = $value;

        return $this;
    }

    /**
     * Nastaveni vice vstupních hodnot
     *
     * @param array $values
     *
     * @return $this
     * @throws \Vzikmund\SmartformApi\Exception\InvalidArgumentException
     */
    public function setValues(array $values) : self
    {
        foreach ($values as $type => $value) {
            $this->addValue($type, $value);
        }

        return $this;
    }

    /**
     * Pridat do hledání Cesko
     * @return $this
     */
    public function addCountryCz() : self
    {
        return $this->addCountry("CZ");
    }

    /**
     * Pridat do hledání Slovensko
     * @return $this
     */
    public function addCountrySk() : self
    {
        return $this->addCountry("SK");
    }

    /**
     * Pridat do hledani novou zemi
     *
     * @param string $country
     *
     * @return $this
     */
    private function addCountry(string $country) : self
    {
        if (!in_array($country, $this->countries)) {
            $this->countries[] = $country;
        }

        return $this;
    }

    /**
     * Vstupní hodnoty
     * @return array
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * Seznam kódů států
     * @return array
     */
    public function getCountries() : array
    {
        return $this->countries;
    }

    /**
     * @return array
     */
    public function getConfiguration() : array
    {
        return $this->configuration;
    }

    /**
     * Považovat města Bratislava a Košice za jednu obec
     *
     * @param bool $val
     *
     * @return $this
     */
    public function setSkBratislavaKosiceUndivided(bool $val = true) : self
    {
        $this->configuration[ "SK_BRATISLAVA_KOSICE_UNDIVIDED" ] = $val ? "true" : "false";

        return $this;
    }

    /**
     * Pražské obvody praha 1 - 10 jsou považovány za obce
     * @param bool $val
     *
     * @return $this
     */
    public function setCzPrahaDividedInto10Parts(bool $val = true){
        $this->configuration[ "CZ_PRAHA_DIVIDED_INTO_10_PARTS" ] = $val ? "true" : "false";

        return $this;
    }


}