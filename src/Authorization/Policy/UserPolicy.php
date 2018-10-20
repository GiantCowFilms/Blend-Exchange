<?php declare(strict_types=1);

namespace BlendExchange\Authorization\Policy;
use BlendExchange\Authorization\User;
use BlendExchange\User\Model\User as UserModel;
use BlendExchange\Authentication\Token\StatelessToken;
use BlendExchange\Authentication\Token\StatelessTokenParser;
use BlendExchange\Authentication\Token\StatelessTokenGenerator;
use BlendExchange\Authentication\Token\StatelessTokenValidator;

final class UserPolicy
{
    public function __construct (StatelessTokenValidator $tokenValidator) {
        $this->tokenValidator = $tokenValidator;
    }

    public function tokenBearerCanSetup(StatelessToken $token,UserModel $user) {
        return $user->email === null && $this->tokenValidator->validate($token, 'SetupUser', $user->id);
    }

    public function userCanView (User $user,UserModel $userModel) {
        return (
            (
            $user->getId() === $userModel->id
            && $user->hasPermission('ViewPrivateSettings')
            ) ||
            $user->hasPermission('AdministerUsers')
        );
    }
}