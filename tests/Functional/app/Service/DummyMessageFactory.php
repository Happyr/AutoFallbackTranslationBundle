<?php

namespace Happyr\AutoFallbackTranslationBundle\Tests\Functional\app\Service;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Psr7\Request;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class DummyMessageFactory implements MessageFactory
{
    public function createRequest(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return new Request($method, $uri);
    }

    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return new Response(200);
    }
}
