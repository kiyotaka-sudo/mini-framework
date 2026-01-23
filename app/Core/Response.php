<?php

namespace App\Core;

class Response
{
    protected string $body;
    protected int $status;
    protected array $headers;

    public function __construct(string $body = '', int $status = 200, array $headers = [])
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = array_change_key_case($headers, CASE_LOWER);
        if (!isset($this->headers['content-type'])) {
            $this->headers['content-type'] = 'text/html; charset=utf-8';
        }
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function withStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[strtolower($name)] = $value;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $name => $value) {
                header(sprintf('%s: %s', ucfirst($name), $value));
            }
        }

        echo $this->body;
    }
}
