<?php declare(strict_types=1);

final class UploadBlendCest
{
    public function _createBlend(ApiTester $I,&$endpoint, &$token) {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/blends/create',[
            'data' => [
                'termsAndPrivacy' => true, 
                'certifyUnderstanding' =>  true, 
                'questionLink' => 'https://blender.stackexchange.com/q/15045', 
                'fileNames' => [
                    'blendFile' => 'Test.blend'
                ]
            ]
        ]);
        $token = $I->grabDataFromResponseByJsonPath('$.token')[0];
        $endpoint = $I->grabDataFromResponseByJsonPath('$.endpoint')[0];
        return $I;
    }

    public function validBlendUploadWorks(ApiTester $I)
    {
        $endpoint;
        $token;
        $this->_createBlend($I,$endpoint,$token);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('X-Resource-Token',$token);
        $I->sendPOST($endpoint,file_get_contents(__DIR__ . '/../../TestBlends/Valid_Compressed.blend'));
        $I->seeResponseCodeIs(200);
        
        // Todo
        // verify file is in google drive and database is updated
    }

    public function duplicateBlendUploadFails(ApiTester $I)
    {
        $endpoint;
        $token;
        $this->_createBlend($I,$endpoint,$token);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('X-Resource-Token',$token);
        $I->sendPOST($endpoint,file_get_contents(__DIR__ . '/../../TestBlends/Valid_Compressed.blend'));
        $I->seeResponseCodeIs(200);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('X-Resource-Token',$token);
        $I->sendPOST($endpoint,file_get_contents(__DIR__ . '/../../TestBlends/Valid_Compressed.blend'));
        $I->seeResponseCodeIs(404);
    }

    public function badTokenBlendUploadFails(ApiTester $I) {
        $endpoint;
        $token;
        $this->_createBlend($I,$endpoint,$token);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('X-Resource-Token',substr($token, 0, strrpos($token, ".",-1)));
        $I->sendPOST($endpoint,file_get_contents(__DIR__ . '/../../TestBlends/Valid_Compressed.blend'));
        $I->seeResponseCodeIs(404);
    }


}
