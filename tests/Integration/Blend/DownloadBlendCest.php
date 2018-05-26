<?php declare(strict_types=1);

final class DownloadBlendCest
{
    public function blendEndpointReturnsBlend(ApiTester $I)
    {
        $I->sendGET('/api/blends');
        $firstBlendId = $I->grabDataFromResponseByJsonPath('$.data[0].id');
        $firstBlendFileName = $I->grabDataFromResponseByJsonPath('$.data[0].fileName');
        $I->sendGET('/d/' . $firstBlendId[0] .'/' . $firstBlendFileName[0]);
        $I->seeResponseCodeIs(200);
    }
}
