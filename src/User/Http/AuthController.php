<?php declare(strict_types=1);

namespace BlendExchange\User\Http;

use AlexMasterov\OAuth2\Client\Provider;
use BlendExchange\Api\ApiResponseFactory;
use BlendExchange\Api\Request\RequestDecoder;

use BlendExchange\Authentication\Token\StatelessTokenFactory;

use BlendExchange\Authentication\Token\StatelessTokenGenerator;
use BlendExchange\Authentication\Token\StatelessTokenParser;
use BlendExchange\Authentication\Token\StatelessTokenValidator;


use BlendExchange\Authentication\Token\UserAuthenticationTokenGenerator;
use BlendExchange\User\Command\AuthenticateUser;
use BlendExchange\User\Command\AuthenticateUserHandler;
use BlendExchange\User\Command\CreateUser;

use BlendExchange\User\Command\CreateUserHandler;
use BlendExchange\User\Data\StackExchangeRepository;
use BlendExchange\User\Data\UserRepository;
use BlendExchange\User\Http\Transformer\AuthenticationTransformer;
use BlendExchange\User\Http\Transformer\PendingAccountTransformer;
use BlendExchange\User\Model\Authentication;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthController
{
    private $oauthClient;
    private $stackExchangeRepository;
    private $userRepository;
    private $tokenGenerator;
    private $tokenFactory;

    public function __construct(
        Provider\StackExchange $oauthClient,
        StackExchangeRepository $stackExchangeRepository,
        UserRepository $userRepository,
        CreateUserHandler $createUserHandler,
        StatelessTokenGenerator $tokenGenerator,
        StatelessTokenValidator $tokenValidator,
        ApiResponseFactory $api,
        StatelessTokenFactory $tokenFactory,
        AuthenticateUserHandler $authenticateUserHandler
    )
    {
        $this->oauthClient = $oauthClient;
        $this->stackExchangeRepository = $stackExchangeRepository;
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenValidator = $tokenValidator;
        $this->api = $api;
        $this->tokenFactory = $tokenFactory;
        $this->createUserHandler = $createUserHandler;
        $this->authenticateUserHandler = $authenticateUserHandler;
    }

    public function redirect(Request $request) : Response
    {
        return new RedirectResponse($this->oauthClient->getAuthorizationUrl());
    }

    public function setupToken(Request $request,PendingAccountTransformer $transformer) : Response
    {
        $stackToken = $this->stackExchangeRepository->getAccessTokenByCode($request->query->get('code'));

        $stackUser = $this->stackExchangeRepository->getUserByAccessToken($stackToken);



        $user = $this->userRepository->getUserByStackId((string) $stackUser->account_id);

        if ($user === null) {
            //Create User
            $user = $this->createUserHandler->handle(
                new CreateUser(
                    (string) $stackUser->account_id, $stackUser->display_name, 
                    (string) $stackToken
                )
            );
        }

        $token = $this->tokenGenerator->generate($user->id, 'SetupUser');
        
        return $this->api->requiresModificationResponse(
            $transformer,
            $user,
            $token,
            '/users/authenticate'
        );
    }

    public function token(Request $request,AuthenticationTransformer $transformer, RequestDecoder $requestDecoder) : Response
    {
        $token = $this->tokenFactory->createFormToken($request);
        $user = $this->userRepository->findUserById($token->getSubject());
        if (
            $user === null || 
            !$this->tokenValidator->validate($token, 'SetupUser', $user->id)
        ) {
            return $this->api->errorResponse('Token Rejected.');
        }

        $auth = $this->authenticateUserHandler->handle(new AuthenticateUser(
            $user,
            (string) ($requestDecoder->decode($request)->data->password ?? null)
        ));

        if ($auth) {
           return $this->api->itemResponse($transformer,$auth);
        } else {
            return $this->api->errorResponse('Credentials Rejected.',422);
        }

    }
}
