<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\Test\ValidateAddress;


use Tester\Assert;
use Vzikmund\SmartformApi\Test\BaseTest;
/**
 * @var string $clientId
 * @var string $password
 */
require_once dirname(__DIR__, 2) . "/init.php";

final class CodeTest extends BaseTest
{

    public function testExact(){

        $request = $this->api->createValidateAddressRequest(1);
        $request->addValue($request::valueCode, "27489272");

        $response = $request->validate();

        Assert::true($response->isResultType($response::resultTypeHit));
        Assert::null($response->errorMessage);

        Assert::null($response->getHint());
        Assert::count(1, $response->getAddresses());

        $address = $response->getAddresses()[0];
        Assert::true($address->isExact());
        Assert::same("Rychlonožkova", $address->getValue($address::valueStreetName));
        Assert::null($address->getValue($address::valueCityArea1Name));
        Assert::same("Brno-venkov", $address->getValue($address::valueDistrictName));

        $coordinates = $address->getCoordinates();
        Assert::true($coordinates->isExact());

        $realEstateDetails = $address->getRealEstateDetails();
        Assert::same("Kuřim", $realEstateDetails->getValue($realEstateDetails::valueCadastralUnitName));
        Assert::same("false", $realEstateDetails->getValue($realEstateDetails::valueBuildingWithLift));
        Assert::same("2", $realEstateDetails->getValue($realEstateDetails::valueNumberOfStoreys));

    }

    public function testNothing(){

        $request = $this->api->createValidateAddressRequest(1);
        $request->addValue($request::valueCode, "30000000");

        $response = $request->validate();

        Assert::true($response->isResultType($response::resultTypeNothing));
        Assert::null($response->errorMessage);
        Assert::count(0, $response->getAddresses());
        Assert::null($response->getHint());

    }

}
(new CodeTest($clientId, $password))->run();
