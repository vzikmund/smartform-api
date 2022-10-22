<?php

declare(strict_types = 1);

namespace Vzikmund\SmartformApi\Exception;

use Throwable;

class SmartApiException extends \Exception
{

    /** @var int */
    private $httpCode;

    public function __construct(int $httpCode, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->httpCode = $httpCode;
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }

    /**
     * Http kod z API
     * @return int
     */
    public function getHttpCode():int{
        return $this->httpCode;
    }

}