<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\Test;

use Tester\Assert;
use Vzikmund\SmartformApi\ValidatePerson\Request;

/**
 * @var string $clientId
 * @var string $password
 */
require_once dirname(__DIR__, 2) . "/init.php";


final class ValidatePersonNameParts extends BaseTest
{

    /** @var array */
    private $requestFields;

    public function __construct(string $clientId, string $password) {
        $this->requestFields = [
            Request::fieldSex,
            Request::fieldFirstname,
            Request::fieldFirstnameVocative,
            Request::fieldLastname,
            Request::fieldLastnameVocative,
            Request::fieldFullName,
            Request::fieldTitleBefore,
            Request::fieldTitleAfter,
            Request::fieldSalutation,
            Request::fieldSalutationVocative
        ];
        parent::__construct($clientId, $password);

    }

    public function testFullname(){

        $request = $this->api->createValidatePersonRequest(1);
        $request
            ->setInputField(Request::fieldTitle, "")
            ->setInputField(Request::fieldFirstname, "Jan")
            ->setInputField(Request::fieldLastname, "Novák")
            ->setRequestFields($this->requestFields);
        $response = $request->validate();

        Assert::null($response->errorMessage);
        Assert::count(count($this->requestFields), $response->getOutputFields());
        Assert::null($response->getOutputField("non-existing"));

        $field = $response->getOutputField(Request::fieldSex);
        Assert::same("M", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldFirstname);
        Assert::same("Jan", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldFirstnameVocative);
        Assert::same("Jane", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldLastnameVocative);
        Assert::same("Nováku", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldFullName);
        Assert::same("Jan Novák", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldTitleBefore);
        Assert::same("", $field->value);
        Assert::true($field->isResultInsufficientData());
        Assert::notNull($field->hint);
        Assert::match("Na vstupu chybí jeden ze sloupců %a%", $field->hint);

        $field = $response->getOutputField(Request::fieldTitleAfter);
        Assert::same("", $field->value);
        Assert::true($field->isResultInsufficientData());
        Assert::notNull($field->hint);
        Assert::match("Na vstupu chybí jeden ze sloupců %a%", $field->hint);

        $field = $response->getOutputField(Request::fieldSalutation);
        Assert::same("Pan", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldSalutationVocative);
        Assert::same("Vážený pane", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

    }


}
(new ValidatePersonNameParts($clientId, $password))->run();