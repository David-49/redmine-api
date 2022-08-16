<?php

namespace OuestCode\RedmineApi;

use GuzzleHttp\Client as Http;
use OuestCode\RedmineApi\Entities\Issue;
use OuestCode\RedmineApi\Entities\Project;
use OuestCode\RedmineApi\Entities\TimeEntry;
use OuestCode\RedmineApi\Entities\Version;
use OuestCode\RedmineApi\Entities\Response;

class HttpHandler
{
    private ?Http $http = null;

    public function __construct(
        protected string $baseUri,
        protected ?string $username = null,
        protected ?string $password = null
    ) {
    }

    public function setAuth(string $username, string $password = null): static
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    public function setHttpClient(Http $http): static
    {
        $this->http = $http;

        return $this;
    }

    public function sendRequest(string $method, string $uri, array $params = []): array
    {
        $http = $this->http ?: new Http([
            'base_uri' => $this->baseUri,
            'auth' => [$this->username, $this->password]
        ]);

        $key = in_array($method, ['post', 'put']) ? 'json' : 'query';

        $response = $http->request($method, $uri, [$key => $params]);

        $body = json_decode(
            json: $response->getBody()->getContents(),
            associative: null,
            flags: JSON_OBJECT_AS_ARRAY
        );

        return [
            'body' => $body,
            'statusCode' => $response->getStatusCode(),
            'baseUri' => $http->getConfig('base_uri'),
        ];
    }
}
