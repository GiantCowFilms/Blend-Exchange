<?php declare(strict_types=1);

namespace BlendExchange\User\Http\Form;

use BlendExchange\Api\Request\RequestDecoder;
use Symfony\Component\HttpFoundation\Request;

final class SetupUserFormFactory 
{

    private $requestDecoder;
    public function __construct (RequestDecoder $requestDecoder)
    {
        $this->requestDecoder = $requestDecoder;
    }

    public function createFromRequest(Request $request)
    {
        $data = $this->requestDecoder->decode($request)->data;
        return new SetupUserForm(
            (string) ($data->email ?? ''),
            (string) ($data->password ?? ''),
            (bool)($data->usePassword ?? false),
            (bool)($data->termsAndPrivacy ?? false)
        );
    }
}