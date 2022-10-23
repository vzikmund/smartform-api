<?php

declare(strict_types = 1);

namespace Vzikmund\SmartformApi\Test;

use Tester\Assert;

/**
 * @var string $clientId
 * @var string $password
 */
require_once dirname(__DIR__) . "/init.php";

final class ValidateEmailTest extends BaseTest
{

    public function testValid(){

        $request = $this->api->createValidateEmailRequest();
        $result = $request->validate("info@smartform.cz");
        Assert::true($result->isOk());
        Assert::null($result->errorMessage);
        Assert::true($result->exists());
        Assert::count(0, $result->resultFlags);
        Assert::null($result->hint);

    }

    public function testNonValid(){

        $request = $this->api->createValidateEmailRequest();
        $result = $request->validate("nonvalid@seznam.cz");
        Assert::true($result->isOk());
        Assert::null($result->errorMessage);
        Assert::false($result->exists());
        Assert::count(1, $result->resultFlags);
        Assert::true($result->isMailboxNotFound());
        Assert::null($result->hint);

    }

    public function testBadDomainHint(){

        $request = $this->api->createValidateEmailRequest();
        $result = $request->validate("smartform@outlok.cz");
        Assert::true($result->isOk());
        Assert::null($result->errorMessage);
        Assert::false($result->exists());
        Assert::count(1, $result->resultFlags);
        Assert::true($result->isBadDomain());
        Assert::notNull($result->hint);
        Assert::same("smartform@outlook.com", $result->hint);

    }


}
(new ValidateEmailTest($clientId, $password))->run();