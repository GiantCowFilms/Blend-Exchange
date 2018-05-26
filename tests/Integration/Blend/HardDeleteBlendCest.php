<?php declare(strict_types=1);

final class HardDeleteBlendCest {
    public function hardDeleteBlendReturnsEndpointNotFoundForNonAdminUser(ApiTester $I) {
        $I->sendGET('/api/blends');
        $firstBlendId = $I->grabDataFromResponseByJsonPath('$.data[0].id');
        $I->sendGET('/api/blends/' . $firstBlendId[0] . '/hard_delete');
        $I->seeEndpointNotFoundResponse();
    }
}