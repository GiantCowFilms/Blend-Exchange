<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module
{
    public function blendTransformerJsonFormat () {
        return [
            'id' => 'string',
            'fileName' => 'string',
            'views_count' => 'integer',
            'downloads_count' => 'integer',
        ];
    }

    public function userProfileTransformerJsonFormat() {
        return             [
            'id' => 'string',
            'username' => 'string',
        ];
    }

    public function userTransformerJsonFormat() {
        return [
            'email' => 'string'
        ];
    }

    /**
     * @return array Only includes fields *not* found in the public format
     */
    public function adminBlendTransformerJsonFormat () {
        return [
            'owner' => 'string',
            'date' => 'string'
        ];
    }

    public function seeEndpointNotFoundResponse() {
        $api = $this->getModule('REST');
        $api->seeResponseCodeIs(404);
        $api->seeResponseContainsJson([
            'type' => 'error',
            'error' => 'Endpoint was not found.'
        ]);
    }

    public function amAuthenticatedAsAdmin() {
        //Todo

    }

    public function amAuthenticatedAsUser() {

    }
}
