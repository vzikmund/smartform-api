<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi;


use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Vzikmund\SmartformApi;

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
     * @return SmartformApi\ValidateEmail\Request
     */
    public function createValidateEmailRequest():SmartformApi\ValidateEmail\Request{
        return new SmartformApi\ValidateEmail\Request($this->client, $this->logHandlers);
    }

    /**
     * @param int $id
     *
     * @return \Vzikmund\SmartformApi\ValidatePerson\Request
     */
    public function createValidatePersonRequest(int $id):SmartformApi\ValidatePerson\Request{
        return new SmartformApi\ValidatePerson\Request($id, $this->client, $this->logHandlers);
    }

}