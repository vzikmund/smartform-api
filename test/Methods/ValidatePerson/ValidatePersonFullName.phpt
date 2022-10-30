<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\Test\ValidatePerson;

use Tester\Assert;
use Vzikmund\SmartformApi\Test\BaseTest;
use Vzikmund\SmartformApi\ValidatePerson\Request;

/**
 * @var string $clientId
 * @var string $password
 */
require_once dirname(__DIR__, 2) . "/init.php";


final class ValidatePersonFullName extends BaseTest
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
            ->addInputField(Request::fieldFullName, "Jan Novák")
            ->setRequestFields($this->requestFields);
        $response = $request->validate();

        Assert::null($response->errorMessage);
        Assert::count(count($this->requestFields), $response->getOutputFields());

        Assert::null($response->getOutputField("non-existing"));

        $field = $response->getOutputField(Request::fieldSex);
        Assert::same("M", $field->value);
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

        $field = $response->getOutputField(Request::fieldTitleBefore);
        Assert::same("", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldSalutation);
        Assert::same("Pan", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldSalutationVocative);
        Assert::same("Vážený pane", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

    }
    public function testFirstname(){

        $request = $this->api->createValidatePersonRequest(1);
        $request
            ->addInputField(Request::fieldFullName, "Anna")
            ->setRequestFields($this->requestFields);
        $response = $request->validate();

        Assert::null($response->errorMessage);

        $field = $response->getOutputField(Request::fieldSex);
        Assert::same("Ž", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldFirstnameVocative);
        Assert::same("Anno", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldLastnameVocative);
        Assert::same("", $field->value);
        Assert::true($field->isResultNothing());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldTitleBefore);
        Assert::same("", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldSalutation);
        Assert::same("Paní", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldSalutationVocative);
        Assert::same("Vážená paní", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

    }
    public function testMultipleDegree(){

        $request = $this->api->createValidatePersonRequest(1);
        $request
            ->addInputField(Request::fieldFullName, "Prof. Ing. Csc Vlasta Koníčková")
            ->setRequestFields($this->requestFields);
        $response = $request->validate();

        Assert::null($response->errorMessage);

        $field = $response->getOutputField(Request::fieldSex);
        Assert::same("Ž", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldFirstnameVocative);
        Assert::same("Vlasto", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldLastname);
        Assert::same("Koníčková", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldLastnameVocative);
        Assert::same("Koníčková", $field->value);
        Assert::true($field->isResultFilledIn());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldFullName);
        Assert::same("prof. Ing. Vlasta Koníčková, CSc.", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);


        $field = $response->getOutputField(Request::fieldTitleBefore);
        Assert::same("prof. Ing.", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

        $field = $response->getOutputField(Request::fieldTitleAfter);
        Assert::same("CSc.", $field->value);
        Assert::true($field->isResultHit());
        Assert::null($field->hint);

    }


}
(new ValidatePersonFullName($clientId, $password))->run();