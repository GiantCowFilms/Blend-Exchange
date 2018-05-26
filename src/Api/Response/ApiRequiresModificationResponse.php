<?php declare(strict_types = 1);

namespace BlendExchange\Api\Response;

use BlendExchange\Authorization\Token;

final class ApiRequiresModificationResponse extends ApiResponse {
    private $item;
    private $token;
    private $endpoint;

    public function __construct ($item,Token\StatelessToken $token,string $endpoint) {
        $this->item = $item;
        $this->token = $token;
        $this->endpoint = $endpoint;
        parent::__construct();
    }

    protected function getType() {
        return 'requires_modification';
    }

    private function getModificationToken() : string
    {
        return (string)$this->token;
    }

    protected function getApiData () : array
    {
        return [
            'type' => $this->getType(),
            'modification_token' => 'Bearer ' . $this->getModificationToken(),
            'item' => $this->item,
            'endpoint' => $this->endpoint
        ];
    }
}