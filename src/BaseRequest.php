<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi;


use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Vzikmund\SmartformApi\Exception\SmartApiException;

abstract class BaseRequest
{

    /** @var \GuzzleHttp\Client */
    private $client;
    /** @var iterable */
    private $logHandlers;

    public function __construct(Client $client, iterable $logHandlers){
        $this->client = $client;
        $this->logHandlers = $logHandlers;
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array  $data
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Vzikmund\SmartformApi\Exception\SmartApiException
     */
    protected function call(string $uri, string $method, array $data):array{

        $this->log(">> calling method $method {$uri}", $data);

        try{

            $response = $this->client->request($method, $uri, [RequestOptions::JSON => $data]);
            $httpCode = $response->getStatusCode();

            # neuspesna autentizace
            if($httpCode === 401){
                throw new SmartApiException($httpCode, "Unauthorized. Check your clientId and password");
            }

            # chyba v odpovedi
            if($httpCode !== 200){
                throw new SmartApiException($httpCode, "Api returned http code !== 200");
            }

            $content = json_decode($response->getBody()->getContents(), true);
            $this->log("<< response code {$httpCode}", ["response_content" => $content]);

            # vysledek volani sluzby
            if($content["resultCode"] === BaseResponse::resultCodeFail){
                throw new SmartApiException(500, "Api call was not successful. Result code === " . BaseResponse::resultCodeFail);
            }

            return $content;

        } catch (SmartApiException $e){
            $context = [
                "http_code" => $e->getHttpCode(),
                "message" => $e->getMessage()
            ];
            $this->log("SmartApiException caught", $context);
            throw $e;
        } catch (\Exception $e){
            $context = ["message" => $e->getMessage(), "code" => $e->getCode()];
            $this->log("Exception caught", $context);
            throw $e;
        }

    }


    /**
     * Vepsani logu
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    private function log(string $message, array $context):void{
        foreach ($this->logHandlers as $handler){
            $handler($message, $context);
        }
    }

}