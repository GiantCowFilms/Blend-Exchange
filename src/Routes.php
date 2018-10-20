<?php declare(strict_types = 1);

use Symfony\Component\Routing\Route;

return [
    /**
     * Site
     */

    'about' => (new Route('/api/about', [
        '_controller' => 'BlendExchange\Site\Http\SiteController::about'
    ]))->setMethods(['GET']),
    'privacy' => (new Route('/privacy', [
        '_controller' => 'BlendExchange\Site\Http\SiteController::privacyPolicy'
    ]))->setMethods(['GET']),
    'terms' => (new Route('/terms', [
        '_controller' => 'BlendExchange\Site\Http\SiteController::termsOfService'
    ]))->setMethods(['GET']),
    
    /**
     * User Auth
     */

    'redirect' => (new Route('/auth/redirect', [
        '_controller' => 'BlendExchange\User\Http\AuthController::redirect'
    ]))->setMethods(['GET']),

    'get_setup_token' => (new Route('/api/auth/setup_token', [
        '_controller' => 'BlendExchange\User\Http\AuthController::setupToken'
    ]))->setMethods(['GET']),

    'get_token' => (new Route('/api/auth/token', [
        '_controller' => 'BlendExchange\User\Http\AuthController::token'
    ]))->setMethods(['GET','POST']),

    /**
     * User
     */

    'current_user' => (new Route('/api/users/current', [
        '_controller' => 'BlendExchange\User\Http\UserController::current'
    ]))->setMethods(['GET']),

    'user' => (new Route('/api/users/{id}', [
        '_controller' => 'BlendExchange\User\Http\UserController::show'
    ]))->setMethods(['GET']),

    'user_profile' => (new Route('/api/users/{id}/profile', [
        '_controller' => 'BlendExchange\User\Http\UserController::profile'
    ]))->setMethods(['GET']),

    'setup_user' => (new Route('/api/users/{id}/setup', [
        '_controller' => 'BlendExchange\User\Http\UserController::setup'
    ]))->setMethods(['POST']),

    'update_user' => (new Route('/api/users/{id}/update', [
        '_controller' => 'BlendExchange\User\Http\UserController::update'
    ]))->setMethods(['POST']),

    /**
     * Blend
     */

    'blends' => (new Route('/api/blends', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::index'
    ]))->setMethods(['GET']),

    'blend' => (new Route('/api/blends/{id}', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::show'
    ]))->setMethods(['GET']),

    'create_blend' => (new Route('/api/blends/create', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::create'
    ]))->setMethods(['POST']),

    'upload_blend' => (new Route('/api/blends/{id}/upload', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::upload'
    ]))->setMethods(['POST']),

    'download_blend' => (new Route('/api/blends/download/{id}/{filename}', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::download'
    ]))->setMethods(['GET'])->setRequirements([
        'req' => '.+\.blend'
    ]),

    'download_blend_short' => (new Route('/d/{id}/{filename}', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::download'
    ]))->setMethods(['GET'])->setRequirements([
        'req' => '.+\.blend'
    ]),

    'favorite_blend' => (new Route('/api/blends/{id}/favorite', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::favorite'
    ]))->setMethods(['POST']),

    'request_blend_deletion' => (new Route('/api/blends/{id}/request_deletion', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::request_deletion'
    ]))->setMethods(['POST']),

    'hard_delete_blend' => (new Route('/api/blends/{id}/hard_delete', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::hardDelete'
    ]))->setMethods(['POST']),

    'soft_delete_blend' => (new Route('/api/blends/{id}/soft_delete', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::softDelete'
    ]))->setMethods(['POST']),

    'admin_comment_blend' => (new Route('/api/blends/{id}/admin_comment', [
        '_controller' => 'BlendExchange\Blend\Http\BlendController::adminComment'
    ]))->setMethods(['POST']),


    /**
     * Flags
     */

    'flag_blend' => (new Route('/api/blends/{blendId}/flag', [
        '_controller' => 'BlendExchange\Flag\Http\FlagController::create'
    ]))->setMethods(['POST']),

    'decline_flag' => (new Route('/api/flag/{id}/decline', [
        '_controller' => 'BlendExchange\Flag\Http\FlagController::decline'
    ]))->setMethods(['POST']),

    'accept_flag' => (new Route('/api/flag/{id}/accept', [
        '_controller' => 'BlendExchange\Flag\Http\FlagController::accept'
    ]))->setMethods(['POST']),

    /**
     * Ads
     */

    'ad' => (new Route('/ads/{name}.png', [
        '_controller' => 'BlendExchange\Ads\Http\AdController::show'
    ]))->setMethods(['GET']),

    /**
     * Api Not Found Page
     */

    'endpoint_not_found' => (new Route('/api/{req}', [
        '_controller' => 'BlendExchange\Site\Http\SiteController::endpointNotFound',
        'req' => null
    ]))->setRequirements([
        'req' => ".+"
    ]),

    /**
     * Singe Page App Launcher
     */

    'main_page' => (new Route('/{req}', [
        '_controller' => 'BlendExchange\Site\Http\SiteController::dynamicPage',
        'req' => null
    ]))->setRequirements([
        'req' => ".+"
    ]),

    /**
     * Maintenance Mode
     */
    'maintenance_mode' => new Route('', [
        '_controller' => 'BlendExchange\Site\Http\SiteController::maintenance_mode'
    ])

];
