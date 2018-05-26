<?php declare(strict_types=1);

final class BlendsCest {
    public function blendsIndexEndpointWorks (ApiTester $I) {
        $I->sendGET('/api/blends');
        $I->seeResponseCodeIs(200);
    }

    public function blendsIndexReturnsListOfBlends (ApiTester $I) {
        $I->sendGET('/api/blends');
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            $I->blendTransformerJsonFormat()
        ], '$.data');
    }
}