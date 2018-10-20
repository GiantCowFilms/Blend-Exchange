<?php
namespace BlendExchange\Blend\Command;

use BlendExchange\Inputs\QuestionLink;
use BlendExchange\Authorization\User;


final class CreateBlend
{
    private $user;
    private $questionLink;
    private $termsAndPrivacy;
    private $uploaderIp;
    private $fileName;

    public function __construct(User $owner, string $questionLink,$uploaderIp,$fileName) {
        $this->user = $owner;
        $this->questionLink = $questionLink;
        $this->uploaderIp = $uploaderIp;
        $this->fileName = $fileName;
    }

    /**
     * Get the question link
     * 
     * @return string
     */
    public function getQuestionLink() : string 
    {
        return $this->questionLink;
    }

    /**
     * Get the uploader's ip address
     * 
     * @return string
     */

     public function getUploaderIp () : string
     {
         return $this->uploaderIp;
     }

    /**
     * Get the file's name
     * 
     * @return string
     */

    public function getFileName () : string
    {
        return $this->fileName;
    }

    /**
     * Get the user's id
     * 
     * @return string The user's id
     */

    public function getOwner () : ?string
    {
        return $this->user->getId();
    }

        /**
     * Get the user
     * 
     * @return User The user
     */

    public function getUser () : User
    {
        return $this->user;
    }

}