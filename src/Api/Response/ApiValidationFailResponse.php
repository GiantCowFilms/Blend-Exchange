<?php declare(strict_types = 1);

namespace BlendExchange\Api\Response;

final class ApiValidationFailResponse extends ApiResponse {
    private $errors;

    public function __construct (array $errors) {
        $this->errors = $errors;
        parent::__construct();
    }

    protected function getCode() : int
    {
        return 422;
    }

    protected function getApiData () : array
    {
        return [
            'error' => 'Validation Failed',
            'errors' => $this->errors
        ];
    }
}