<?php

declare(strict_types = 1);

namespace Vzikmund\SmartformApi\ValidateEmail;

use Vzikmund\SmartformApi\BaseRequest;

final class Request extends BaseRequest
{

    public function validate(string $emailAddress)
    {
        $response = $this->call("validateEmail/v1", "POST", ["emailAddress" => $emailAddress]);

        return new Response($response);
    }
}