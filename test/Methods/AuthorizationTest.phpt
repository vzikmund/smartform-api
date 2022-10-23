<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\Test;

use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;
use Vzikmund\SmartformApi\Api;
use Vzikmund\SmartformApi\Exception\SmartApiException;

require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
Environment::setup();

final class AuthorizationTest extends TestCase
{

    public function testInvalidAuth(){
        Assert::exception(function(){
            $api = new Api("test", "test", false);
            $api->createValidateEmailRequest()->validate("test@gmail.com");
        }, SmartApiException::class, "Unauthorized. Check your clientId and password");
    }

}

(new AuthorizationTest())->run();