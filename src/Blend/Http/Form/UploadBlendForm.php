<?php
namespace BlendExchange\Blend\Http\Form;

use BlendExchange\Input\Data;
use BlendExchange\Blend\Command\UploadBlend;
use BlendExchange\Blend\Validation\BlendFileValidator;
use BlendExchange\Blend\Model\BlendFile;

final class UploadBlendForm
{
    private $errors = [];
    private $blend;

    /**
     * @param resource $handle
     */
    public function __construct(BlendFileValidator $blendFileValidator,$handle) {
        $this->blendFileValidator = $blendFileValidator;
        $stream = (new Data($handle))->getStream();
        $this->blend = new \BlendExchange\Application\BlendFile\BlendFileFormat($stream);
        $this->validate();
    }

    /**
     * Runs validation for the class.
     */
    private function validate() : void
     {
        if(!$this->blendFileValidator->validate($this->blend->getUncompressedStream())) {
            $this->errors['blendFile'] = "All uploaded files must be in the blend file format";
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
     * @param BlendFile $blend
     * @return UploadBlend
     */
    public function toCommand (BlendFile $blend) : UploadBlend
     {
        return new UploadBlend(
            $this->blend->getCompressedStream(),
            $blend->id
        );
    }
}