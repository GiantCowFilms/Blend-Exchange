<?php  declare(strict_types = 1);

namespace BlendExchange\Flag\Http\Form;

use Symfony\Component\HttpFoundation\Request;

use BlendExchange\Repositories\BlendRepository;
use BlendExchange\Input\IpAddress;

final class CreateFlagFormFactory
{
    public function __construct()
    {

    }

    public function createFromRequest(Request $request): CreateFlagForm
    {
        $data = json_decode($request->getContent())->data;

        return new CreateFlagForm(
            (string) ($data->offense ?? ''),
            (string) ($data->message ?? ''),
            new IpAddress($request->server->get('REMOTE_ADDR'))
        );
    }
}
