<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\Test\ValidatePerson;

use Tester\Assert;
use Vzikmund\SmartformApi\Exception\InvalidArgumentException;
use Vzikmund\SmartformApi\Test\BaseTest;
use Vzikmund\SmartformApi\ValidatePerson\Request;

/**
 * @var string $clientId
 * @var string $password
 */
require_once dirname(__DIR__, 2) . "/init.php";

final class ValidatePersonTest extends BaseTest
{

    public function testRequestFields()
    {
        $request = $this->api->createValidatePersonRequest(1);
        Assert::exception(function () use ($request) {
            $request->setRequestFields(["nesmysl"]);
        }, InvalidArgumentException::class, 'Unknown request field %a%');
        Assert::noError(function () use ($request) {
            $request->setRequestFields([Request::fieldLastname]);
        });
        Assert::count(1, $request->getRequestedFields());
        Assert::equal([["fieldType" => Request::fieldLastname]], $request->getRequestedFields());
    }

    public function testInputFields()
    {
        $request = $this->api->createValidatePersonRequest(1);
        Assert::exception(function () use ($request) {
            $request->addInputField("nesmysl", "hodnota");
        }, InvalidArgumentException::class, 'Unknown input field %a%');

        Assert::noError(function () use ($request) {
            $request
                ->addInputField(Request::fieldFirstname, "Jméno")
                ->addInputField(Request::fieldLastname, "Příjmení");
        });

        Assert::count(2, $request->getInputFields());
        Assert::equal(
            [
                ["fieldType" => Request::fieldFirstname, "value" => "Jméno"],
                ["fieldType" => Request::fieldLastname, "value" => "Příjmení"],
            ],
            $request->getInputFields()
        );
    }

}

(new ValidatePersonTest($clientId, $password))->run();