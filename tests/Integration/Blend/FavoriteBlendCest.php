<?php declare(strict_types=1);

class FavoriteBlendCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function canFavoriteBlend(ApiTester $I) {
        $I->sendGET('/api/blends');
        $firstBlendId = $I->grabDataFromResponseByJsonPath('$.data[0].id');
        $firstBlendFileName = $I->grabDataFromResponseByJsonPath('$.data[0].fileName');
        $I->sendPOST('/api/blends/' . $firstBlendId[0] .'/favorite');
        $I->seeResponseCodeIs(200);
        $I->seeInDatabase('accesses',[
            'type' => 'favorite',
            'fileId' => $firstBlendId[0]
        ]);
    }
}
