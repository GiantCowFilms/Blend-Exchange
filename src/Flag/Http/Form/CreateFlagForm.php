<?php declare(strict_types=1);

namespace BlendExchange\Flag\Http\Form;

use BlendExchange\Flag\Command\CreateFlag;
use BlendExchange\Input\IpAddress;
use BlendExchange\Blend\Model\BlendFile;

final class CreateFlagForm
{
    private $offense;
    private $message;
    private $errors = [];

    public function __construct($offense,$message,IpAddress $ipAddress)
    {
        $this->offense = $offense;
        $this->message = $message;
        $this->ipAddress = $ipAddress;
        $this->validate();
    }

    private function validate() {
        if(!in_array($this->offense,['notSE','virus','copyright','spam','obscene','other'])){
            $this->errors['offense'] = 'Please select a valid offense. If none apply, select other';
        }
        if(strlen($this->message) < 10 || strlen($this->message) > 1024) {
            $this->errors['message'] = 'Message must be between 10 and 1024 characters';
        }
    }

    public function getErrors () {
        return $this->errors;
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

    public function toCommand(BlendFile $blend) : CreateFlag
    {
        return new CreateFlag($blend,$this->ipAddress,$this->offense,$this->message);
    }
}