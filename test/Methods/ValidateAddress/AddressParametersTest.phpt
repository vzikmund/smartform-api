<?php

declare(strict_types = 1);

namespace Vzikmund\SmartformApi\Test\ValidateAddress;



use Tester\Assert;
use Vzikmund\SmartformApi\Exception\InvalidArgumentException;
use Vzikmund\SmartformApi\Test\BaseTest;
use Vzikmund\SmartformApi\ValidateAddress\Request;

/**
 * @var string $clientId
 * @var string $password
 */
require_once dirname(__DIR__, 2) . "/init.php";


final class AddressParametersTest extends BaseTest
{

    public function testAddSingleValue(){

        $request = $this->api->createValidateAddressRequest(1);
        Assert::exception(function() use ($request){
            $request->addValue("uknown", "test");
        }, InvalidArgumentException::class, "Unknown value key %a%");

        $request
            ->addValue(Request::valueStreet, "Ulice")
            ->addValue(Request::valueWholeAddress, "Celá adresa");
        Assert::equal(["STREET" => "Ulice", "WHOLE_ADDRESS" => "Celá adresa"], $request->getValues());

    }

    public function testSetValues(){
        $request = $this->api->createValidateAddressRequest(1);
        Assert::exception(function()use($request){

            $values = [
                Request::valueStreet => "Ulice",
                "uknown" => "test"
            ];
            $request->setValues($values);

        },InvalidArgumentException::class, "Unknown value key %a%");

        $request = $this->api->createValidateAddressRequest(1);
        $values = [
            Request::valueStreet => "Ulice",
            Request::valueWholeAddress => "Celá adresa"
        ];
        $request->setValues($values);
        Assert::equal($values, $request->getValues());

    }

    public function testCountry(){

        $request = $this->api->createValidateAddressRequest(1);
        Assert::equal([], $request->getCountries());

        $request->addCountryCz();
        Assert::equal(["CZ"], $request->getCountries());

        $request->addCountrySk();
        Assert::equal(["CZ", "SK"], $request->getCountries());
    }

}

(new AddressParametersTest($clientId, $password))->run();