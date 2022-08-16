<?php

namespace Tests;

use GuzzleHttp\Client as Http;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use OuestCode\RedmineApi\HttpHandler;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function createHttpMock(string $body = null, int $statusCode = 200): HttpHandler
    {
        $mockHandler = new MockHandler([
            new GuzzleResponse($statusCode, [], $body),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $http = new Http(['handler' => $handlerStack, 'base_uri' => 'https://redmine.org/']);

        $httpHandler = new HttpHandler('https://redmine.org/');
        $httpHandler->setHttpClient($http);

        return $httpHandler;
    }
}
