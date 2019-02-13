<?php

class UpdateUserCest
{
    public function unauthenticatedUserCannotEditUser(ApiTester $I) {
        $firstUserId = $I->grabFromDatabase('users', 'id');
        $I->sendPOST('/api/users/' . $firstUserId . '/update', [
            'username' => 'Mallory',
            'email' => 'mallory@example.com'
        ]);
        $I->seeResponseCodeIs(404);
    }
}
