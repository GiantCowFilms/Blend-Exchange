<?php declare(strict_types=1);

namespace BlendExchange\Blend\Http\Form;

use BlendExchange\Authorization\User;
use BlendExchange\Blend\Command\HardDeleteBlend;
use BlendExchange\Blend\Command\SoftDeleteBlend;
use BlendExchange\Blend\Model\BlendFile;

final class DeleteBlendForm
{
    private $errors = [];
    private $reason;

    public function __construct (string $reason) {
        $this->reason = $reason;
        $this->validate();
    }

    public function validate() {
        if ($this->reason === '') {
            $this->errors['reason'] = 'Please provide a reason for the deletion.';
        } else if(strlen($this->reason) > 1024) {
            $this->errors['reason'] = 'Reason must be less than 1024 characters';
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

    public function toSoftDeleteCommand (User $user, BlendFile $blend) : SoftDeleteBlend
     {
        return new SoftDeleteBlend(
            $user,
            $blend,
            $this->reason
        );
    }

    public function toHardDeleteCommand (User $user, BlendFile $blend) : HardDeleteBlend {
        return new HardDeleteBlend(
            $user,
            $blend,
            $this->reason
        );
    }

}
