<?php declare(strict_types = 1);
namespace BlendExchange\Blend\Http\Form;

use BlendExchange\Input\QuestionLink;
use BlendExchange\Input\IpAddress;
use BlendExchange\Blend\Command\CreateBlend;
use BlendExchange\Input\QuestionLinkValidator;
use BlendExchange\Authorization\User;

final class CreateBlendForm
{
    private $errors = [];
    private $questionLink;
    private $termsAndPrivacy;
    private $certifyUnderstanding;
    private $questionLinkValidator;
    private $fileName;
    private $uploaderIp;

    public function __construct(QuestionLinkValidator $questionLinkValidator, string $questionLink,bool $termsAndPrivacy, bool $certifyUnderstanding, string $fileName,string $uploaderIp) {
        $this->questionLinkValidator = $questionLinkValidator;
        $this->questionLink = new QuestionLink($questionLink);
        $this->termsAndPrivacy = $termsAndPrivacy;
        $this->certifyUnderstanding =$certifyUnderstanding;
        $this->fileName = $fileName;
        $this->uploaderIp = new IpAddress($uploaderIp);
        $this->validate();
    }

    /**
     * Runs validation for the class.
     */
    private function validate() : void
     {
        if(!$this->questionLinkValidator->validate($this->questionLink)) {
            $this->errors['questionLink'] = "You must provide the <b>Question</b> url to an existing question on blender.stackexchange. If you have not posted the question, post it and edit the blend in afterwards.";
        }
        if(!$this->termsAndPrivacy) {
            $this->errors['termsAndPrivacy'] = "You must read the privacy policy and agree to the terms of service.";
        }
        if(!$this->certifyUnderstanding){
            $this->errors['certifyUnderstanding'] = "Please take a moment to read the terms of service so you undrestand the right you hand over. If you have any questions, feel to contact Blend-Exchange.";
        }
        if(!(preg_match('/.+\.blend/',$this->fileName) === 1)) {
            $this->errors['blendFile'] = "The file must be a valid .blend file, ending with the extension .blend";
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

    /**
     * Create command from form
     * 
     * @param User $user Current authenticated user
     * @return CreateBlend
     */
    public function toCommand (User $user) : CreateBlend
     {
        return new CreateBlend(
            $user,
            $this->questionLink->sanitizedUrl(),
            $this->uploaderIp->getAnonymized(),
            $this->fileName
        );
    }
}