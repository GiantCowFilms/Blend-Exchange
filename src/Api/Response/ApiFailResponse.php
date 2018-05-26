<?php declare(strict_types = 1);

namespace BlendExchange\Api\Response;

final class ApiFailResponse extends ApiResponse
{
    private $code;
    private $error;

    public function __construct(string $error, int $code = 500)
    {
        $this->error = $error;
        $this->code = $code;
        parent::__construct();
    }

    protected function getApiData() : array
    {
        return [
            'type' => 'error',
            'error' => $this->error
        ];
    }

    protected function getCode() : int
    {
        return $this->code;
    }
}
