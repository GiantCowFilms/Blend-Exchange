<?php declare(strict_types=1);

namespace BlendExchange\Flag\Http;

use BlendExchange\Api\ApiResponseFactory;
use BlendExchange\Authorization\User;
use BlendExchange\Blend\Data\BlendRepository;
use BlendExchange\Flag\Command\CreateFlagHandler;
use BlendExchange\Flag\Data\FlagRepository;

use BlendExchange\Flag\Http\Form\CreateFlagFormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


final class FlagController
{
    private $user;
    private $createFlagFormFactory;
    private $api;
    private $blendRepository;
    public function __construct(
        User $user,
        CreateFlagFormFactory $createFlagFormFactory,
        CreateFlagHandler $createFlagHandler,
        ApiResponseFactory $api,
        BlendRepository $blendRepository,
        FlagRepository $flagRepository
    )
    {
        $this->user = $user;
        $this->createFlagFormFactory = $createFlagFormFactory;
        $this->blendRepository = $blendRepository;
        $this->api = $api;
        $this->createFlagHandler = $createFlagHandler;
        $this->flagRepository = $flagRepository;
    }

    public function index() : Response {
        return $this->api->errorResponse('Method not implemented.',501);
    }

    public function create ($blendId, Request $request) : Response
    {
        $form = $this->createFlagFormFactory->createFromRequest($request);
        if ($form->hasErrors()) {
            return $this->api->validationFailResponse($form->getErrors());
        }
        $blend = $this->blendRepository->findBlendById($blendId);
        if ($blend === null) {
            return $this->api->errorResponse('Blend file not found.',404);
        }
        $command = $form->toCommand($blend);
        $this->createFlagHandler->handle($command);

        return $this->api->successResponse();
    }

    public function decline($id, Request $request) : Response
    {
        
        $flag = $this->flagRepository->findById($id);
    }

    public function accept($id, Request $request) : Response
    {

    }
}