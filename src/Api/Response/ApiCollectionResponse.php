<?php declare(strict_types=1);

namespace BlendExchange\Api\Response;

final class ApiCollectionResponse extends ApiResponse {
    private $collection;
    
    public function __construct($collection) {
        $this->collection = $collection;
        parent::__construct();
    }

    private function getPage () {
        return $this->collection->currentPage();
    }

    private function getNextPage () {
        min($this->getPage() + 1,$this->collection->lastPage());
    }

    private function getPreviousPage() {
        return max(1,$this->collection->currentPage() - 1);
    }

    protected function getApiData () : array
    {
        return [
            'type' => 'collection',
            'page' => $this->getPage(),
            'next_page' => $this->getNextPage(),
            'previous_page' => $this->getPreviousPage(),
            'items' => $this->collection->items()
        ];
    }
}