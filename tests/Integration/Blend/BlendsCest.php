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

    public function blendIndexCanSortByDownload (ApiTester $I) {
        $I->sendGET('/api/blends?sort=downloads');
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            $I->blendTransformerJsonFormat()
        ], '$.data');
        $blends = $I->grabDataFromResponseByJsonPath('$.data')[0];
        $previous = $blends[0]['downloads_count'];
        foreach($blends as $blend) {
            if ($blend['downloads_count'] > $previous) {
                $I->fail('Blends incorrectly sorted');
            }
            $previous = $blend['downloads_count'];
        }
    }

    public function blendIndexCanFilterByUserId(ApiTester $I) {
        $I->sendGET('/api/blends?owner=DEdzry8A7J&sort=views');
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            $I->blendTransformerJsonFormat()
        ], '$.data');
        $I->seeResponseContainsJson([
            'id' => '07W3q7wD'
        ]);
    }
}