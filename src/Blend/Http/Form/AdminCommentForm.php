<?php declare(strict_types=1);

namespace BlendExchange\Blend\Http\Form;

use BlendExchange\Authorization\User;
use BlendExchange\Blend\Command\AdminComment;
use BlendExchange\Blend\Model\BlendFile;

final class AdminCommentForm
{
    private $errors = [];
    private $adminComment;

    public function __construct (string $adminComment) {
        $this->adminComment = $adminComment;
        $this->validate();
    }

    public function validate() {
        if(strlen($this->adminComment) > 1024) {
            $this->errors['adminComment'] = 'Comment must be less than 1024 characters';
        }
    }

        /**
     * Check if the request has errors
     * 
     * @return bool
     */
    public function hasErrors() : bool
    {
        return count($this->errors) > 0;
    }

    /**
     * Get the request errors
     * 
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    public function toCommand (BlendFile $blend) : AdminComment
     {
        return new AdminComment(
            $blend,
            $this->adminComment
        );
    }

}
