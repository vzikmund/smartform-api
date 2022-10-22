<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\Test;


use Tester\TestCase;
use Vzikmund\SmartformApi\Api;

abstract class BaseTest extends TestCase
{

    /** @var \Vzikmund\SmartformApi\Api */
    protected $api;

    public function __construct(string $clientId, string $password){
        $this->api = new Api($clientId, $password, true);
    }

}