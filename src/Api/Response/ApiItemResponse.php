<?php declare(strict_types = 1);

namespace BlendExchange\Api\Response;

final class ApiItemResponse extends ApiResponse {
    private $item;

    public function __construct ($item) {
        $this->item = $item;
        parent::__construct();
    }

    protected function getApiData(): array
    {
        return [
            'type' => 'item',
            'item' => $this->item
        ];
    }
}