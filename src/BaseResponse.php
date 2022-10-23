<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi;


abstract class BaseResponse
{

    /**
     * Zdali volání služby proběhlo v pořádku.
     * @var string
     */
    public const
        resultCodeOk = "OK",
        resultCodeFail = "FAIL";

    /** @var array */
    protected $content;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function getResultCode() : string
    {
        return $this->content[ "resultCode" ];
    }

    /**
     * Volani sluzby probehlo v poradku
     * @return bool
     */
    public function isOk() : bool
    {
        return $this->getResultCode() === self::resultCodeOk;
    }


    /**
     * @param $property
     *
     * @return mixed|null
     */
    public function __get($property)
    {
        # klic v poli neexistuje
        if (!array_key_exists($property, $this->content)) {
            return null;
        }

        return $this->content[ $property ];
    }

    /**
     * Payload ziskany z API
     * @return array
     */
    public function getResponseContent() : array
    {
        return $this->content;
    }

}

