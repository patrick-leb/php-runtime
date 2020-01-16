<?php

declare(strict_types=1);

/*
 * This file is part of Placeholder PHP Runtime.
 *
 * (c) Carl Alexander <contact@carlalexander.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Placeholder\Runtime\Lambda;

class LambdaResponse implements LambdaResponseInterface
{
    /**
     * The body of the Lambda response.
     *
     * @var string
     */
    private $body;

    /**
     * The headers to send with the Lambda response.
     *
     * @var array
     */
    private $headers;

    /**
     * The HTTP status code of the response.
     *
     * @var int
     */
    private $statusCode;

    /**
     * Constructor.
     */
    public function __construct(string $body, array $headers = [], int $statusCode = 200)
    {
        $this->body = $body;
        $this->headers = $this->formatHeaders($headers);
        $this->statusCode = $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseData(): array
    {
        return [
            'isBase64Encoded' => false,
            'statusCode' => $this->statusCode,
            'multiValueHeaders' => empty($this->headers) ? new \stdClass() : $this->headers,
            'body' => $this->body,
        ];
    }

    /**
     * Format the response headers for the API gateway.
     */
    private function formatHeaders(array $headers): array
    {
        $formattedHeaders = [];

        foreach ($headers as $name => $values) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            $formattedHeaders[$name] = is_array($values) ? array_values($values) : [$values];
        }

        if (!isset($formattedHeaders['Content-Type'])) {
            $formattedHeaders['Content-Type'] = 'text/html';
        }

        return $formattedHeaders;
    }
}
