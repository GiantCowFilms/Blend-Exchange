<?php declare(strict_types=1);
namespace BlendExchange\User\Http\Transformer;

use BlendExchange\User\Model\Authentication;
use League\Fractal\TransformerAbstract;

final class AuthenticationTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'user'
    ];

    public function transform (Authentication $authentication) {
        return [
            'token' => (string) $authentication->token
        ];
    } 

    public function includeUser(Authentication $authentication)
    {
        return $this->item($authentication->user, new UserTransformer);
    }
}