<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Http\Message;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $reasonPhrase;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $protocolVersion;

    /**
     * @var StreamInterface
     */
    private $body;

    /**
     * Response constructor.
     *
     * @param int $statusCode
     * @param array $headers
     * @param StreamInterface $body
     * @param string $protocolVersion
     * @param string $reasonPhrase
     */
    public function __construct(
        $statusCode = 200,
        array $headers = [],
        StreamInterface $body,
        $protocolVersion = '1.1',
        $reasonPhrase = ''
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
        $this->reasonPhrase = $reasonPhrase ?: $this->getReasonPhraseForStatusCode($statusCode);
    }

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     * @return self
     */
    public function withProtocolVersion($version): self
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader($name): bool
    {
        return isset($this->headers[$name]);
    }

    /**
     * @param string $name
     * @return array
     */
    public function getHeader($name): array
    {
        if (!$this->hasHeader($name)) {
            return [];
        }
        return $this->headers[$name];
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * @param string $name
     * @param string|array $value
     * @return self
     */
    public function withHeader($name, $value): self
    {
        $new = clone $this;
        $new->headers[$name] = (array)$value;
        return $new;
    }

    /**
     * @param string $name
     * @param string|array $value
     * @return self
     */
    public function withAddedHeader($name, $value): self
    {
        $new = clone $this;
        if ($new->hasHeader($name)) {
            $new->headers[$name] = array_merge($new->headers[$name], (array)$value);
        } else {
            $new->headers[$name] = (array)$value;
        }
        return $new;
    }

    /**
     * @param string $name
     * @return self
     */
    public function withoutHeader($name): self
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    // Body
    /**
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body
     * @return self
     */
    public function withBody(StreamInterface $body): self
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return self
     */
    public function withStatus($code, $reasonPhrase = ''): self
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase ?: $this->getReasonPhraseForStatusCode($code);
        return $new;
    }

    /**
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * Internal helper to map status codes to reason phrases
     *
     * @param int $code
     * @return string
     */
    private function getReasonPhraseForStatusCode($code)
    {
        $phrases = [
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            // Add other status codes as needed
        ];

        return $phrases[$code] ?? 'Unknown Status';
    }
}
