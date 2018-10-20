<?php declare(strict_types = 1);

namespace BlendExchange\Blend\Http;

use BlendExchange\Access\AccessManager;
use BlendExchange\Api\ApiResponseFactory;
use BlendExchange\Authentication\Token\StatelessTokenGenerator;
use BlendExchange\Authentication\Token\StatelessTokenParser;
use BlendExchange\Authentication\Token\StatelessTokenValidator;
use BlendExchange\Authorization\Policy\BlendPolicy;
use BlendExchange\Authorization\User;
use BlendExchange\Blend\Command\AdminCommentHandler;
use BlendExchange\Blend\Command\CreateBlendHandler;
use BlendExchange\Blend\Command\DestroyBlendHandler;
use BlendExchange\Blend\Command\DownloadBlend;
use BlendExchange\Blend\Command\DownloadBlendHandler;

use BlendExchange\Blend\Command\FavoriteBlend;
use BlendExchange\Blend\Command\FavoriteBlendHandler;

use BlendExchange\Blend\Command\HardDeleteBlendHandler;
use BlendExchange\Blend\Command\SoftDeleteBlendHandler;
use BlendExchange\Blend\Command\UploadBlendHandler;

use BlendExchange\Blend\Data\AdminBlendsQueryFactory;
use BlendExchange\Blend\Data\BlendRepository;
use BlendExchange\Blend\Data\BlendsQueryFactory;
use BlendExchange\Blend\Http\Form\AdminCommentFormFactory;
use BlendExchange\Blend\Http\Form\CreateBlendFormFactory;
use BlendExchange\Blend\Http\Form\DeleteBlendFormFactory;
use BlendExchange\Blend\Http\Form\DestroyBlendFormFactory;
use BlendExchange\Blend\Http\Form\UploadBlendFormFactory;
use BlendExchange\Blend\Http\Form\RequestBlendDeletionFormFactory;
use BlendExchange\Blend\Http\Transformer\AdminBlendTransformer;
use BlendExchange\Blend\Http\Transformer\BlendTransformer;
use BlendExchange\Blend\Model\BlendFile;
use BlendExchange\Models\Access;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlendController
{
    private $user;
    private $api;
    private $blendTransformer;

    public function __construct(
        User $user,
        ApiResponseFactory $api,
        BlendTransformer $blendTransformer,
        BlendRepository $blendRepository,
        AccessManager $accessManager,
        BlendPolicy $blendPolicy,
        StatelessTokenGenerator $tokenGenerator,
        StatelessTokenParser $tokenParser,
        StatelessTokenValidator $tokenValidator,
        DeleteBlendFormFactory $deleteBlendFormFactory, 
        FavoriteBlendHandler $favoriteBlendHandler,
        AdminBlendTransformer $adminBlendTransformer,
        SoftDeleteBlendHandler $softDeleteBlendHandler,
        HardDeleteBlendHandler $hardDeleteBlendHandler,
        AdminCommentFormFactory $adminCommentFormFactory,
        AdminCommentHandler $adminCommentHandler
    ) {
        $this->user = $user;
        $this->api = $api;
        $this->blendTransformer = $blendTransformer;
        $this->blendRepository = $blendRepository;
        $this->accessManager = $accessManager;
        $this->blendPolicy = $blendPolicy;
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenParser = $tokenParser;
        $this->tokenValidator = $tokenValidator;
        $this->favoriteBlendHandler = $favoriteBlendHandler;
        $this->adminBlendTransformer = $adminBlendTransformer;
        $this->deleteBlendFormFactory = $deleteBlendFormFactory;
        $this->softDeleteBlendHandler = $softDeleteBlendHandler;
        $this->hardDeleteBlendHandler = $hardDeleteBlendHandler;
        $this->adminCommentFormFactory = $adminCommentFormFactory;
        $this->adminCommentHandler = $adminCommentHandler;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, BlendsQueryFactory $blendsQueryFactory, AdminBlendsQueryFactory $adminQueryFactory) : Response
    {
        if (!$this->user->hasPermission('ListBlends')) {
            return $this->api->errorResponse('You cannot view the blend file list');
        }
        $blendsQuery = $blendsQueryFactory->createFromRequest($request, $this->user);
        if ($blendsQuery->hasErrors()) {
            return $this->api->validationFailResponse($blendsQuery->getErrors());
        }
        if ($this->user->hasPermission('AdminQuery')) {
            $query = $adminQueryFactory->createFromRequest($request, $blendsQuery);
        } else {
            $query = $blendsQuery;
        }
        $paginator = $query->execute();
        return $this->api->paginationResponse($this->blendTransformer, new IlluminatePaginatorAdapter($paginator), $paginator->getCollection());
    }

    /**
     * Create a new .blend
     *
     * @return Response
     */
    public function create(Request $request, CreateBlendFormFactory $createBlendFormFactory, CreateBlendHandler $createBlendHandler): Response
    {
        if (!$this->user->hasPermission('CreateBlend')) {
            return $this->api->errorResponse('You do not have permission to upload a .blend file');
        }
        $createForm = $createBlendFormFactory->createFromRequest($request);
        if ($createForm->hasErrors()) {
            return $this->api->validationFailResponse($createForm->getErrors());
        }
        $createCommand = $createForm->toCommand($this->user);
        $blend = $createBlendHandler->handle($createCommand);
        $token = $this->tokenGenerator->generate($blend->id, 'UploadBlend');
        return $this->api->requiresModificationResponse($this->blendTransformer, $blend, $token, sprintf('/api/blends/%s/upload', $blend->id));
    }

    /**
     * Upload a new .blend
     *
     * @param string $id unique identifier for blend file.
     * @return Response
     */
    public function upload(
        $id,
    Request $request,
    UploadBlendFormFactory $uploadBlendFormFactory,
    UploadBlendHandler $uploadBlendHandler
    ): Response {
        if (!$this->user->hasPermission('CreateBlend')) {
            return $this->api->errorResponse('You do not have permission to upload a .blend file');
        }

        $token = $this->tokenParser->parseToken($request->headers->get('X-Resource-Token'));

        $blend = $this->blendRepository->findIncompleteBlendById($id);

        if (
            $blend === null ||
            $token === null ||
            !$this->blendPolicy->tokenBearerCanUpload($token, $blend)
        ) {
            return $this->api->errorResponse('Blend not found',404);
        }

        $uploadForm = $uploadBlendFormFactory->createFromRequest($request);
        if ($uploadForm->hasErrors()) {
            return $this->api->validationFailResponse($uploadForm->getErrors());
        }
        return $this->api->itemResponse($this->blendTransformer, $uploadBlendHandler->handle($uploadForm->toCommand($blend)));
    }

    /**
     * Download a .blend
     *
     * @param string $id unique identifier for blend file.
     * @return Response
     */
    public function download(
        $id,
        Request $request,
        DownloadBlendHandler $downloadBlendHandler
    ) : Response {
        $blend = $this->blendRepository->findBlendById($id);
        if ($blend === null) {
            return $this->api->errorResponse('Blend not found', 404);
        }
        if (!$this->blendPolicy->userCanDownload($this->user, $blend) || !$this->user->hasPermission('DownloadBlend')) {
            return $this->api->errorResponse('You cannot download this file.');
        }
        $this->accessManager->download($blend->id, $request->server->get("REMOTE_ADDR"));
        $stream = $downloadBlendHandler->handle(new DownloadBlend($blend->id));
        return $this->api->streamResponse($stream);
    }

    public function show($id, Request $request) : Response
    {
        $blend = $this->blendRepository->findBlendById($id);
        if ($blend === null) {
            return $this->api->errorResponse('Blend not found', 404);
        }
        $this->accessManager->view($blend->id, $request->server->get("REMOTE_ADDR"));
        if ($this->user->hasPermission('ModerateBlend')) {
            return $this->api->itemResponse($this->adminBlendTransformer, $blend);
        } else {
            return $this->api->itemResponse($this->blendTransformer, $blend);
        }
    }

    public function favorite($id, Request $request) : Response
    {
        if ($this->user->hasPermission('FavoriteBlend')) {
            return $this->api->itemResponse($this->adminBlendTransformer, $blend);
        }
        $blend = $this->blendRepository->findBlendById($id);
        if ($blend === null) {
            return $this->api->validationFailResponse([
                'id' => 'A blend file with this id could not be found.'
            ], 422);
        }
        $command = new FavoriteBlend(
            $blend->id,
            new \BlendExchange\Input\IpAddress($request->server->get('REMOTE_ADDR'))
        );
        $this->favoriteBlendHandler->handle($command);

        return $this->api->successResponse();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function request_deletion(
        Request $request,
        string $id,
        RequestBlendDeletionFormFactory $requestBlendDeletionFormFactory,
        RequestBlendDeletionHandler $requestBlendDeletionHandler,
        BlendPolicy $blendPolicy
    ) : Response {
        throw new \Exception('Method not implemented');
        $blend = $this->blendRepository->findBlendById($id);
        if ($blend === null) {
            return $this->api->errorResponse('Blend file not found', 404);
        }
        if (!$blendPolicy->userCanRequestDeletion($this->user, $blend)) {
            return $this->api->errorResponse('You cannot request the removal of this blend', 403);
        }

        $requestBlendDeletionForm = $requestBlendDeletionFormFactory->createFromRequest($request);
        if ($requestBlendDeletionForm->hasErrors()) {
            return $this->api->validationFailResponse($requestBlendDeletionForm->getErrors());
        }
        $blendDeletionRequest = $requestBlendDeletionHandler->handle($requestBlendDeletionForm->toCommand($this->user));
        return new Response\ApiItemResponse($blendDeletionRequest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) : Response
    {
        return $this->api->errorResponse('Not Implemented',501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function hardDelete(Request $request, $id)
    {
        if (!$this->user->hasPermission('HardDeleteBlend')) {
            return $this->api->notFoundResponse();
        }
        $blend = $this->blendRepository->findBlendById($id);
        if ($blend === null) {
            return $this->api->errorResponse('Blend not found', 404);
        }
        $form = $this->deleteBlendFormFactory->createFromRequest($request);
        if ($form->hasErrors()) {
            return $this->api->validationFailResponse($form->getErrors());
        }
        $blend = $this->hardDeleteBlendHandler->handle($form->toHardDeleteCommand($this->user,$blend));
        return $this->api->successResponse('Blend was deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function softDelete(Request $request, $id)
    {
        if (!$this->user->hasPermission('SoftDeleteBlend')) {
            return $this->api->notFoundResponse();
        }

        $blend = $this->blendRepository->findBlendById($id);
        if ($blend === null) {
            return $this->api->errorResponse('Blend not found', 404);
        }

        $form = $this->deleteBlendFormFactory->createFromRequest($request);
        if ($form->hasErrors()) {
            return $this->api->validationFailResponse($form->getErrors());
        }

        $this->softDeleteBlendHandler->handle($form->toSoftDeleteCommand($this->user,$blend));
        return $this->api->successResponse('Blend was deleted');
    }

    public function adminComment(Request $request, $id) {
        if(!$this->user->hasPermission('AdminComment')) {
            return $this->api->notFoundResponse();
        }

        $blend = $this->blendRepository->findBlendById($id);
        if ($blend === null) {
            return $this->api->errorResponse('Blend not found', 404);
        }

        $form = $this->adminCommentFormFactory->createFromRequest($request);
        if ($form->hasErrors()) {
            return $this->api->validationFailResponse($form->getErrors());
        }

        $this->adminCommentHandler->handle($form->toCommand($blend));
        return $this->api->successResponse('Comment was posted');

    }
}
