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

final class ConfigurationTest extends BaseTest
{


    public function testSkDivided(){

        $request = $this->api->createValidateAddressRequest(1);
        $request
            ->addCountrySk()
            ->addValue($request::valueMunicipalityAndDistrict, "Bratislava");

        $response = $request->validate();

        Assert::true($response->isResultType($response::resultTypeTooMany));
        Assert::true(count($response->getAddresses()) > 1);

        foreach ($response->getAddresses() as $address){
            Assert::true($address->isPartial());
            Assert::true($address->getCoordinates()->isApproximate());
            Assert::null($address->getRealEstateDetails());
        }

        $hint = $response->getHint();
        Assert::same("Bylo nalezeno více částečných adres: 'obec Bratislava-Čunovo', 'obec Bratislava-Lamač', 'obec Bratislava-Jarovce', 'obec Bratislava-Rusovce', 'obec Bratislava-Devín' a další.", $hint->getMessage());
        Assert::null($hint->getAddresses());

    }

    public function testSkUndivided(){

        $request = $this->api->createValidateAddressRequest(1);
        $request
            ->addCountrySk()
            ->setSkBratislavaKosiceUndivided()
            ->addValue($request::valueMunicipalityAndDistrict, "Bratislava");

        $response = $request->validate();

        Assert::true($response->isResultType($response::resultTypePartialHit));
        Assert::count(1, $response->getAddresses());
        Assert::null($response->getHint());

    }


}
(new ConfigurationTest($clientId,$password))->run();