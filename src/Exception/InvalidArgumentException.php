<?php

declare(strict_types = 1);


namespace Vzikmund\SmartformApi\Exception;


use Throwable;

final class InvalidArgumentException extends SmartApiException
{

    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct(400, $message, $code, $previous);
    }

}