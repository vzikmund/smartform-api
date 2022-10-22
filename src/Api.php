<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi;


use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Vzikmund\SmartformApi\ValidateEmail\Request;
use Vzikmund\SmartformApi\ValidateEmail\Response;

class Api
{

    /** @var \GuzzleHttp\Client */
    private $client;

    /** @var callable[] */
    private $logHandlers = [];

    public function __construct(string $clientId, string $apiPassword, bool $isTest)
    {
        $config = [
            'base_uri'                  => 'https://secure.smartform.cz/smartform-ws/',
            RequestOptions::AUTH        => [$clientId, $apiPassword], # basic auth
            RequestOptions::HEADERS     => [],
            RequestOptions::HTTP_ERRORS => false # vypnuti vyhazovani vyjimek pri chybovych stavech
        ];

        # oznacit requesty jako test
        if ($isTest) {
            $config[ RequestOptions::HEADERS ][ "test" ] = true;
        }

        $this->client = new Client($config);
    }

    /**
     * Nastaveni log handleru
     *
     * @param callable $logHandler
     *
     * @return $this
     */
    public function addLogHandler(callable $logHandler) : self
    {
        $this->logHandlers[] = $logHandler;

        return $this;
    }


    /**
     *
     * @param string $emailAddress
     *
     * @return \Vzikmund\SmartformApi\ValidateEmail\Response
     */
    public function validateEmail(string $emailAddress):Response{
        return (new Request($this->client, $this->logHandlers))->validate($emailAddress);
    }

}