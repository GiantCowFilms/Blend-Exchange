<?php declare(strict_types = 1);

namespace BlendExchange\User\Http;

use BlendExchange\Authorization\User;
use Symfony\Component\HttpFoundation;

use BlendExchange\Api\ApiResponseFactory;

use BlendExchange\User\Data\UserRepository;

use Symfony\Component\HttpFoundation\Request;
use BlendExchange\User\Http\Form\SetupUserForm;
use BlendExchange\User\Command\SetupUserHandler;
use BlendExchange\Authorization\Policy\UserPolicy;
use BlendExchange\User\Http\Form\SetupUserFormFactory;

use BlendExchange\User\Http\Transformer\UserTransformer;

use BlendExchange\User\Http\Transformer\ProfileTransformer;
use BlendExchange\Authentication\Token\StatelessTokenParser;
use BlendExchange\Authentication\Token\StatelessTokenFactory;
use BlendExchange\Authentication\Token\StatelessTokenGenerator;
use BlendExchange\Authentication\Token\StatelessTokenValidator;


class UserController
{
    private $user;
    private $api;
    public function __construct (
        User $user,
        ApiResponseFactory $api,
        ProfileTransformer $profileTransformer,
        UserTransformer $userTransformer,
        SetupUserFormFactory $setupUserFormFactory,
        SetupUserHandler $setupUserHandler,
        UserRepository $userRepository,
        StatelessTokenParser $tokenParser,
        StatelessTokenValidator $tokenValidator,
        StatelessTokenFactory $tokenFactory,
        UserPolicy $userPolicy
    ) {
        $this->user = $user;
        $this->api = $api;
        $this->profileTransformer  = $profileTransformer;
        $this->setupUserFormFactory = $setupUserFormFactory;
        $this->setupUserHandler = $setupUserHandler;
        $this->userRepository = $userRepository;
        $this->tokenParser = $tokenParser;
        $this->tokenValidator = $tokenValidator;
        $this->tokenFactory = $tokenFactory;
        $this->userPolicy = $userPolicy;
        $this->userTransformer = $userTransformer;
    }

    public function setup ($id,Request $request) {
        $setupForm = $this->setupUserFormFactory->createFromRequest($request);
        if( $setupForm->hasErrors()) {
            return $this->api->validationFailResponse($setupForm->getErrors());
        }

        $token = $this->tokenFactory->createFormToken($request);
        $user = $this->userRepository->findIncompleteUserById($id);

        if (
            $user === null ||
            $token === null ||
            !$this->userPolicy->tokenBearerCanSetup($token, $user)
        ) {
            return $this->api->errorResponse('User not found.',404); 
        }

        $setupUserCommand = $setupForm->toCommand($user);
        $this->setupUserHandler->handle($setupUserCommand);
        return $this->api->itemResponse($this->profileTransformer, $user);
    }

    public function authenticate (Request $request) {
        $authenticateUserForm = $authenticateUserFormFactory->createFromRequest($request);
        $authenticateUserCommand = $authenticateUserForm->toCommand(); new AuthenticateUserCommand($request->request->get('password'));
        try {
            $authenticateCommandHandler->handle($authenticateCommand);
        } catch (Exceptions\AuthenticationException $error) {
            return new Response\ApiFailResponse($error->message,403);
        }
    }

    public function current () {
        if (!$this->user->hasPermission('CurrentUser')) {
            return $this->api->errorResponse('You are not logged in.',403);
        }
        $user = $this->userRepository->findUserById($this->user->getId());
        return $this->api->itemResponse($this->userTransformer,$user);
    }

    public function profile ($id) {
        $user = $this->userRepository->findUserById($id);
        return $this->api->itemResponse($this->profileTransformer,$user);
    }

    public function update ($id) {
        return $this->api->errorResponse('Not implemented.',501);
    }

    public function show($id) {
        if (!$this->user->hasPermission('ViewPublicProfile')) {
            return $this->api->endpointNotFoundResponse();
        }
        $user = $this->userRepository->findUserById($id);
        if ($user === null) {
            return $this->api->errorResponse('User not found.',404);
        }
        if ($this->userPolicy->userCanView($this->user, $user) && $this->user->hasPermission('ViewPrivateSettings')) {
            return $this->api->itemResponse($this->userTransformer,$user);
        } else {
            return $this->api->itemResponse($this->profileTransformer,$user);
        }
    }
}