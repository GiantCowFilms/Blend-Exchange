<?php declare(strict_types=1);

use GuzzleHttp\Client;

final class GetAnswer extends Endpoint
{

    public function execute(AnswerQuery $query) {
        $apiResponse = $this->httpClient->request('GET','/answer/',[
            'query' => (array) $query
        ]);
        new ApiPage::$apiResponse;
    }
}