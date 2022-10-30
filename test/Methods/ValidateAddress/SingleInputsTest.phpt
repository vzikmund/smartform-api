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

final class SingleInputsTest extends BaseTest
{

    public function testTypo(){

        $request = $this->api->createValidateAddressRequest(1);
        $request
            ->setValues(
                [
                    $request::valueFirstLine => "Kočanská 42",
                    $request::valueSecondLine => "Praha",
                    $request::valueZip => "10100"
                ]
            );

        $response = $request->validate();

        Assert::true($response->isOk());
        Assert::null($response->errorMessage);
        Assert::null($response->getHint());

        Assert::count(1, $response->getAddresses());
        $address = $response->getAddresses()[0];

        Assert::true($address->getCoordinates()->isExact());
        Assert::same("Praha 10 - Vršovice", $address->getValue($address::valueSecondLine));
    }

}
(new SingleInputsTest($clientId, $password))->run();