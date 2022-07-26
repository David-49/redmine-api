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

    public function sendRequest(string $method, string $uri, array $params = []): Response
    {
        $http = $this->http ?: new Http([
            'base_uri' => $this->baseUri,
            'auth' => [$this->username, $this->password]
        ]);

        $response = $http->request($method, $uri, ['query' => $params]);

        $body = json_decode(
            json: $response->getBody()->getContents(),
            associative: null,
            flags: JSON_OBJECT_AS_ARRAY
        );

        $items = $this->extractItemsFromBody($body);

        return new Response(
            items: $items,
            offset: $body['offset'] ?? null,
            limit: $body['limit'] ?? null,
            total: $body['total_count'] ?? count($items)
        );
    }

    protected function extractItemsFromBody(array $body): array
    {
        list($items, $class) = match (true) {
            array_key_exists('projects', $body) => [$body['projects'], Project::class],
            array_key_exists('issues', $body) => [$body['issues'], Issue::class],
            array_key_exists('issue', $body) => [[$body['issue']], Issue::class],
            array_key_exists('versions', $body) => [$body['versions'], Version::class],
            array_key_exists('time_entries', $body) => [$body['time_entries'], TimeEntry::class]
        };

        return array_map(
            fn(array $properties) => new $class($properties),
            $items
        );
    }
}
