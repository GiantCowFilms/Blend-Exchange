<?php declare(strict_types=1);

namespace BlendExchange\User\Data;

use BlendExchange\User\Model\User;

final class UserRepository
{
    public function __construct () 
    {

    }

    public function getUserByStackId(string $stackId) : ?User
    {
        return User::where('stackId',$stackId)->first();
    } 

    public function findIncompleteUserById(string $id) : ?User
    {
        return User::where('id',$id)->first();
    }

    public function findUserById(string $id) : ?User
    {
        return User::where('id',$id)->first();
    }
}