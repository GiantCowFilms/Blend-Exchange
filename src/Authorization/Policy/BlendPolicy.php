<?php declare(strict_types=1);

namespace BlendExchange\Authorization\Policy;
use BlendExchange\Blend\Model\BlendFile;
use BlendExchange\Authorization\User;
use BlendExchange\Authentication\Token\StatelessToken;
use BlendExchange\Authentication\Token\StatelessTokenGenerator;
use BlendExchange\Authentication\Token\StatelessTokenValidator;
use BlendExchange\Authentication\Token\StatelessTokenParser;

final class BlendPolicy
{
    public function __construct (StatelessTokenValidator $tokenValidator) {
        $this->tokenValidator = $tokenValidator;
    }

    public function userCanDownload(User $user,BlendFile $blend) : bool
    {
        if ($user->hasPermission('DownloadBlend')) {
            if($blend->isVisible()) {
                return true;
            } else if ($user->hasPermission('ModerateBlend') && $blend->isDownloadable()) {
                return true;
            }
        }
        return false;
    }

    public function userCanRequestDeletion(User $user,BlendFile $blend) : bool
    {
        if($user->getId() !== null) {
            if($user->id === $blend->owner) {
                return true;
            }
            if ($user->hasPermission('DestroyBlend')) {
                return true;
            }
        }
        return false;
    }

    public function tokenBearerCanUpload(StatelessToken $token,BlendFile $blend) {
        return $this->tokenValidator->validate($token, 'UploadBlend', $blend->id);
    }
}