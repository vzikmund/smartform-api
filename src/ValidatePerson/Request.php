<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\ValidatePerson;


use GuzzleHttp\Client;
use Vzikmund\SmartformApi\BaseRequest;
use Vzikmund\SmartformApi\Exception\InvalidArgumentException;

final class Request extends BaseRequest
{

    public const
        fieldFirstname = "FIRSTNAME", fieldFirstnameVocative = "FIRSTNAME_VOCATIVE",
        fieldLastname = "LASTNAME", fieldLastnameVocative = "LASTNAME_VOCATIVE",
        fieldFullName = "FULLNAME",
        fieldTitle = "TITLE", fieldTitleBefore = "TITLE_BEFORE", fieldTitleAfter = "TITLE_AFTER",
        fieldSalutation = "SALUTATION", fieldSalutationVocative = "SALUTATION_VOCATIVE",
        fieldSex = "SEX";

    /** @var array */
    private $inputFields = [],
        $requestedFields = [];

    /**
     * Identifikace dotazu – slouží jen pro spárování dotazu a odpovědi. Pro validaci není důležité.
     * @var int
     */
    private $id;

    public function __construct(int $id, Client $client, array $logHandlers)
    {
        $this->id = $id;
        parent::__construct($client, $logHandlers);
    }


    /**
     * @return \Vzikmund\SmartformApi\ValidatePerson\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Vzikmund\SmartformApi\Exception\SmartApiException
     */
    public function validate():Response
    {
        $inputFields   = $this->getInputFields();
        $requestFields = $this->getRequestedFields();

        $data = [
            "id"              => $this->id,
            "inputFields"     => $inputFields,
            "requestedFields" => $requestFields,
        ];

        $response = $this->call("validatePerson/v2", "POST", $data);
        return new Response($response);

    }

    /**
     * Formatovane vstupni hodnoty
     * @return array
     */
    public function getInputFields() : array
    {
        if (count($this->inputFields) === 0) {
            return [];
        }

        $res = [];
        foreach ($this->inputFields as $type => $value) {
            $fieldType = [
                "fieldType" => $type,
                "value"     => $value,
            ];
            $res[]     = $fieldType;
        }

        return $res;
    }

    /**
     * Formatovany pozadavek na vystupni hodnoty
     * @return array
     */
    public function getRequestedFields() : array
    {
        return $this->requestedFields;
    }

    /**
     * Nastavení vstupní hodnoty.
     *
     * @param string $type
     * @param string $value
     *
     * @return $this
     * @throws \Vzikmund\SmartformApi\Exception\InvalidArgumentException
     */
    public function setInputField(string $type, string $value) : self
    {
        $constants = $this->getConstants();
        if (!in_array($type, $constants)) {
            throw new InvalidArgumentException("Unknown input field '{$type}'");
        }
        $this->inputFields[ $type ] = $value;

        return $this;
    }

    /**
     * Nastaveni seznamu požadovaných výstupních hodnot.
     *
     * @param array $fields
     *
     * @return $this
     * @throws \Vzikmund\SmartformApi\Exception\InvalidArgumentException
     */
    public function setRequestFields(array $fields) : self
    {
        $res       = [];
        $constants = $this->getConstants();
        foreach ($fields as $field) {
            if (!in_array($field, $constants)) {
                throw new InvalidArgumentException("Unknown request field '$field'");
            }
            $res[] = ["fieldType" => $field];
        }

        $this->requestedFields = $res;

        return $this;
    }

    /**
     * Definovane konstanty ve tride
     * @return array
     */
    private function getConstants() : array
    {
        $reflection = new \ReflectionClass($this);

        return $reflection->getConstants();
    }


}