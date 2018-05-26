<?php  declare(strict_types = 1);
namespace BlendExchange\Blend\Http\Form;

use Symfony\Component\HttpFoundation\Request;
use BlendExchange\Input\QuestionLinkValidator;
use BlendExchange\Api\Request\RequestDecoder;

final class CreateBlendFormFactory
{
    private $user;
    private $requestDecoder;

    public function __construct(QuestionLinkValidator $questionLinkValidator,RequestDecoder $requestDecoder)
    {
        $this->questionLinkValidator = $questionLinkValidator;
        $this->requestDecoder = $requestDecoder;
    }

    public function createFromRequest(Request $request): CreateBlendForm
    {
        $data = $this->requestDecoder->decode($request)->data;

        return new CreateBlendForm(
            $this->questionLinkValidator,
            (string) ($data->questionLink ?? ''),
            (bool)($data->termsAndPrivacy ?? false),
            (bool)($data->certifyUnderstanding ?? false),
            (string) ($data->fileNames->blendFile ?? ''),
            (string) $request->server->get('REMOTE_ADDR')
        );
    }
}
