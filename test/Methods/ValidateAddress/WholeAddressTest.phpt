<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\Test\ValidateAddress;


use Tester\Assert;
use Vzikmund\SmartformApi\Test\BaseTest;
use Vzikmund\SmartformApi\ValidateAddress\Address\Coordinates;
use Vzikmund\SmartformApi\ValidateAddress\Address\RealEstateDetails;
use Vzikmund\SmartformApi\ValidateAddress\Hint;

/**
 * @var string $clientId
 * @var string $password
 */
require_once dirname(__DIR__, 2) . "/init.php";


final class WholeAddressTest extends BaseTest
{

    public function testStreetTypo()
    {
        $request = $this->api->createValidateAddressRequest(1);

        $request
            ->addValue($request::valueWholeAddress, "Tomáše Bati 44, Zlín, 760 01")
            ->addCountryCz();
        $response = $request->validate();

        Assert::true($response->isOk());
        Assert::null($response->errorMessage);
        Assert::true($response->isResultType($response::resultTypeHit));
        Assert::null($response->getHint());

        Assert::count(1, $response->getAddresses());

        $address = $response->getAddresses()[ 0 ];
        Assert::true($address->isExact());
        Assert::false($address->isPartial());

        Assert::same("4208056", $address->getValue($address::valueCode));
        Assert::same("třída Tomáše Bati", $address->getValue($address::valueStreetName));
        Assert::null($address->getValue("unknown"));

        $coordinates = $address->getCoordinates();
        Assert::type(Coordinates::class, $coordinates);
        Assert::true($coordinates->isExact());
        Assert::same(1165196.16, $coordinates->jtskX);
        Assert::same(520675.26, $coordinates->jtskY);
        Assert::same(49.2253864, $coordinates->gpsLat);
        Assert::same(17.6723046, $coordinates->gpsLng);
        Assert::null($coordinates->unknown);

        $realEstateDetails = $address->getRealEstateDetails();
        Assert::type(RealEstateDetails::class, $realEstateDetails);
        Assert::same("6361", $realEstateDetails->getValue($realEstateDetails::valueBuildingParcelNumber1));
        Assert::null($realEstateDetails->getValue($realEstateDetails::valueBuildingParcelNumber2));
        Assert::same("1879", $realEstateDetails->getValue($realEstateDetails::valueFloorArea));
    }

    public function testHint()
    {
        $request = $this->api->createValidateAddressRequest(1);
        $request->addValue($request::valueWholeAddress, "Koperníkova 1221, Plzeň, 30100")
            ->addCountryCz();

        $response = $request->validate();
        Assert::true($response->isResultType($response::resultTypePartialHit));

        $hint = $response->getHint();
        Assert::type(Hint::class, $hint);
        Assert::same(
            $hint->getMessage(),
            "Byla nalezena ulice 'Koperníkova', ale ne číslo '1221'. Existují podobná čísla '1222/42', '1201/57' a '1224/51'."
        );
        Assert::null($hint->getAddresses());

        Assert::count(1, $response->getAddresses());

        $address = $response->getAddresses()[ 0 ];
        Assert::true($address->isPartial());
        Assert::null($address->getRealEstateDetails());
        Assert::type(Coordinates::class, $address->getCoordinates());
    }

    public function testMany()
    {
        $request = $this->api->createValidateAddressRequest(1);
        $request->addValue($request::valueWholeAddress, "Trojská 25 Praha")
            ->addCountryCz();
        $response = $request->validate();

        Assert::same(1, $response->id);
        Assert::true($response->isResultType($response::resultTypeMany));

        Assert::count(2, $response->getAddresses());

        $hint = $response->getHint();
        Assert::same($hint->getMessage(), "Bylo nalezeno více adres: 'Trojská 25/142, 171 00 Praha 7 - Troja' a 'Trojská 107/25, 182 00 Praha 8 - Kobylisy'.");
        Assert::null($hint->getAddresses());

        foreach ($response->getAddresses() as $address){
            Assert::true($address->isExact());
        }

    }

    public function testEmptyRealEstateDetails(){

        $request = $this->api->createValidateAddressRequest(1);
        $request
            ->addValue($request::valueWholeAddress, "Bédy Křídla, Kopidlno")
            ->addCountryCz();

        $response = $request->validate();
        Assert::true($response->isResultType($response::resultTypePartialHit));
        Assert::count(1, $response->getAddresses());

        $address = $response->getAddresses()[0];
        Assert::true($address->isPartial());
        Assert::false($address->isExact());
        Assert::null($address->getRealEstateDetails());

        Assert::null($response->getHint());
    }


}

(new WholeAddressTest($clientId, $password))->run();