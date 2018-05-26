<?php declare(strict_types=1);

final class UserCest
{
    public function userEndpointReturnsUserProfile(ApiTester $I)
    {
        $firstUserId = $I->grabFromDatabase('users', 'id');

        $I->sendGET('/api/users/' . $firstUserId);
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType(
            $I->userProfileTransformerJsonFormat(),
            '$.data'
        );
    }

    public function userEndpointDoesNotReturnPrivateUserInfo(ApiTester $I) {
        $firstUserId = $I->grabFromDatabase('users', 'id');

        $I->sendGET('/api/users/' . $firstUserId);
        $I->seeResponseCodeIs(200);
        $I->dontSeeResponseContainsJson(
            $I->userTransformerJsonFormat(),
            '$.data'
        );
    }
}
