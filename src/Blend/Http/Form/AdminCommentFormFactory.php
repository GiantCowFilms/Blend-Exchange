<?php declare(strict_types=1);

namespace BlendExchange\Blend\Http\Form;

use BlendExchange\Api\Request\RequestDecoder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Create AdminCommentFormFactory
 */
final class AdminCommentFormFactory
{
    public function __construct(RequestDecoder $requestDecoder)
    {
        $this->requestDecoder = $requestDecoder;
    }

    public function createFromRequest(Request $request) : AdminCommentForm
    {
        $data = $this->requestDecoder->decode($request)->data;
        return new AdminCommentForm(($data->adminComment ?? ''));
    }
}
