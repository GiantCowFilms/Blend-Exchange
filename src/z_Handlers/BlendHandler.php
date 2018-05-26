<?php declare(strict_types = 1);

namespace BlendExchange\Handlers;
use BlendExchange\Blend\Model\BlendFile;
use BlendExchange\Client\StackExchangeClient;
use BlendExchange\Exceptions\ValidationException;
use BlendExchange\Models\Access;
/**
 * This class has become sort of a dumping ground for blend related functionality including validation, sanitization and modification.
 */
class BlendHandler {
    private $google_drive_service;
    private $stack_client;
    public function __construct (\BlendExchange\Client\StackExchangeClient $stack_client, \Google_Service_Drive $google_drive_service) {
        $this->google_drive_service = $google_drive_service;
        $this->stack_client = $stack_client;
    }

    public function getQuestionId ($questionLink) {
        if ($questionLink === null) {
            return null;
        }
        $matches = [];
        $state = (preg_match("/^https?:\/\/(?:meta\.)?blender.stackexchange.com\/q(?:uestions)?\/([0-9]+)(?:\/(?:[A-z\-#0-9\/_?=&]+|[0-9]+|))?$/", $questionLink, $matches)) ? 1 : 0;
        return ($state) ? $matches[1] : null;
    }

    /**
     * 
     */
    public function validateUrl($questionLink) {
        $errors = [];
        $question = $this->getQuestionId($questionLink);
        if ($question !== null) {
            $question = $this->stack_client->getQuestion($question);
        }
        if ($question === null) {
            $errors[] = "The provided question url must be a valid link to an existing question on blender.stackexchange. Be sure not to use the answer link.";
        }
        return $errors;
    }

    public function isCompressed($handle) {
        rewind($handle);
        return fread($handle,7) !== 'BLENDER';
    }

    public function verifyBlendFormat($handle) {
        rewind($handle);
        $reader = function ($length) use ($handle) { rewind($handle); return fread($handle,$length); };
        if ($this->isCompressed($handle)) {
            $handle2 = gzopen(stream_get_meta_data($handle)["uri"],'rb');
            $reader = function ($length) use ($handle2) { rewind($handle2); return gzread($handle2,$length); };
        }
        return $reader(7) === 'BLENDER';
    }

    public function sanitizeBlend ($handle) {
        throw new Exception('Not supported!');
    }

    public function validateBlend($handle){
        if($handle === null) {
            $errors[] = "Please select a blend file to upload.";
            return $errors; //Quit here, cannot validate any more rules
        }
        if(!is_resource($handle) || get_resource_type($handle) !== 'stream') {
            //assume symfony file
            $handle = fopen($handle->getRealPath(),"rb");
        }

        $blendSize = fstat($handle)['size']/1000/1024;
        $errors = [];
        if($blendSize > 30) {
            $errors[] = "Blend Files must be smaller than 30 MB. Try to remove unnecessary data.";
        }
        if (!$this->verifyBlendFormat($handle)) {
            $errors[] = "Please upload a file in the .blend format. Images can be uploaded directly to blender.stackexchange. Other formats are not supported.";
        }
        return $errors;
    }

    public function addAccess($type,$ipAddr,$blend,$unique = false) {
        $ip = substr(hash('SHA384',$ipAddr),0,512);
        $access = $blend->favorites->where('ip',$ip)->first();
        if($access !== null && $unique){
            return false;
        }
        $access = new Access();
        $access->fileId = $blend->id;
        $access->type = $type;
        $access->ip = $ip;
        $access->save();
        return true;
    }

    public function gzipToTemp ($handle) {
        $temp = tmpfile();
        $gzTemp = gzopen(stream_get_meta_data($temp)["uri"],'wb9');
        $chunkSizeBytes = 1 * 1024 * 1024;
        rewind($handle);
        while(!feof($handle)) {
            gzwrite($gzTemp,fread($handle,$chunkSizeBytes));
        }
        gzclose($handle);
        return $temp;
    }

    public function fillBlendWithPostData ($blend,$request) {
        /**
         * Validate input data
         */

        $file = $request->files->get('blendFile');

        if (!$request->request->has('termsAndPrivacy')) {
            $errors['termsAndPrivacy'] = ["You must agree to the terms of service and read the privacy policy."];
        }
        $errors['questionLink'] = $this->validateUrl($request->request->get('questionLink'));
        $errors['blendFile'] = $this->validateBlend($file);
        $errors = array_filter($errors,function ($e) {
            return count($e) !== 0;
        });

        /**
         * Fill Data
         */
        $blend->fill($request->request->all());
        $blend->questionLink = sprintf('https://blender.stackexchange.com/q/%s',$this->getQuestionId($request->request->get('questionLink')));
        $blend->fileName = $file->getClientOriginalName();
        $blend->adminComment = '';
        //Set in the upload handler
        //$blend->fileSize = $dataSize;
        $blend->date = date('Y-m-d H:i:s');
        $blend->valid = 0;
        $blend->password = bin2hex(random_bytes(16));
        $blend->deleted = 0;
        $blend->owner = 0;
        $blend->uploaderIp = substr(hash('SHA384',$request->getClientIp()),0,512);
        return $errors;

    }

    public function createUploadHandler ($blend,$handle) {
        $compress = ! $this->isCompressed($handle);
        if ($compress) {
            $handle = $this->gzipToTemp($handle);
        }
        return new \BlendExchange\Handlers\BlendUploadHandler($this->google_drive_service,$blend,$handle);
    }

    //TODO
    public function createDownloadHandler (BlendFile $blend) {
        return new \BlendExchange\Handlers\BlendDownloadHandler($this->google_drive_service,$blend);
    }

    public function findBlendQuery ($id) {
        return BlendFile::where('id','=',$id)->orWhere('legacy_id','=',$id);
    }

    public function findBlend($id) {
        return $this->findBlendQuery($id)->first();
    }

    public function canDownload($user,$blend) {
        //Check if blend file has any open copyright flags
        try {
            if($blend->flags()->where('val','copyright')->count() > 0) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function attachUploadedFile ($blend,$createdFile) {
        $blend->fileGoogleId = $createdFile->id;
    }

    public function addView ($ipAddr,$blend) {
        $this->addAccess('view',$ipAddr,$blend);
    }

    public function addDownload ($ipAddr,$blend) {
        $this->addAccess('download',$ipAddr,$blend);
    }
    public function addFavorite ($ipAddr,$blend) {
        $this->addAccess('favorite',$ipAddr,$blend,true);
    }
}