<?php declare(strict_types = 1);
namespace BlendExchange\Authorization;

use BlendExchange\Authentication\Token\UserAuthenticationTokenParser;
use BlendExchange\Authentication\Token\UserAuthenticationTokenValidator;
use BlendExchange\Authorization\Role\AdminRole;
use BlendExchange\Authorization\Role\UserRole;
use Lcobucci\JWT\Parser;
use Symfony\Component\HttpFoundation\Request;

final class CurrentUserFactory
{
    private $request;
    private $tokenParser;

    public function __construct(Request $request,UserAuthenticationTokenParser $tokenParser,
    UserAuthenticationTokenValidator $tokenValidator)
    {
        $this->request = $request;
        $this->tokenParser = $tokenParser;
        $this->tokenValidator = $tokenValidator;
    }

    /**
     * Creates an authenticated user based on the HTTP Request
     *
     * @return User 
     */
    public function create() : User
    {
        $authorizationHeader = $this->request->headers->get('Authorization');
        if ($authorizationHeader !== null) {
            $token = $this->tokenParser->parseToken($authorizationHeader);
            if ($token !== null &&$this->tokenValidator->validate($token)) {
                $roles = [new UserRole()];
                if (in_array('admin',$token->roles())) {
                    $roles[] = new AdminRole();
                }
                return new AuthenticatedUser($token->getUserId(),$roles);
            }
        }
        
        return new GuestUser();
    }
}
