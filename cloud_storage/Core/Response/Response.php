<?php

namespace App\Core\Response;

interface ResponseInterface
{
    public function setData();
    public function setHeaders();
}

class Response implements ResponseInterface
{
    private $data;
    private $statusCode;

    public function __construct($data, $statusCode)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }
    public function setData()
    {
        $this->setHeaders();

        $statusCode = is_array($this->statusCode) ? 200 : $this->statusCode;

        http_response_code($statusCode);
        return $this->data;
    }
    public function setHeaders()
    {
        header('Content-Type: application/json');
    }
}
