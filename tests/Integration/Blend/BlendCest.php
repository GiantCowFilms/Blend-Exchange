<?php declare(strict_types=1);

final class BlendCest
{
    public function blendEndpointReturnsBlend(ApiTester $I)
    {
        $I->sendGET('/api/blends');
        $firstBlendId = $I->grabDataFromResponseByJsonPath('$.data[0].id');
        $I->sendGET('/api/blends/' . $firstBlendId[0]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType(
            $I->blendTransformerJsonFormat(),
            '$.data'
        );
    }
}
