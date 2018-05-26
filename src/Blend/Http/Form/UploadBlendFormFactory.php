<?php  declare(strict_types = 1);
namespace BlendExchange\Blend\Http\Form;

use Symfony\Component\HttpFoundation\Request;
use BlendExchange\Blend\Validation\BlendFileValidator;

final class UploadBlendFormFactory
{
    private $user;
    private $stackClient;

    public function __construct(BlendFileValidator $blendValidator)
    {
        $this->blendValidator = $blendValidator;
    }

    public function createFromRequest(Request $request): UploadBlendForm
    {
        
        return new UploadBlendForm(
            $this->blendValidator,
            fopen('php://input','r')
        );
    }
}
