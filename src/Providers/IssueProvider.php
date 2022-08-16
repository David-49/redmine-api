<?php

namespace OuestCode\RedmineApi\Providers;

use OuestCode\RedmineApi\Entities\Issue;
use OuestCode\RedmineApi\Entities\Response;
use OuestCode\RedmineApi\HttpHandler;

class IssueProvider
{
    public function __construct(protected HttpHandler $http)
    {
    }

    public function all(array $params = []): Response
    {
        $response = $this->http->sendRequest('get', 'issues.json', $params);

        $issues = array_map(
            function (array $properties) use ($response) {
                $properties += ['baseUri' => $response['baseUri']];

                return new Issue($properties);
            },
            $response['body']['issues'],
        );

        return new Response(
            statusCode: $response['statusCode'],
            items: $issues,
            offset: $response['body']['offset'] ?? null,
            limit: $response['body']['limit'] ?? null,
            total: $response['body']['total_count'] ?? count($issues)
        );
    }

    public function get(int $id, array $params = []): Response
    {
        $response = $this->http->sendRequest('get', "issues/{$id}.json", $params);

        $issue = new Issue(
            $response['body']['issue'] + ['baseUri' => $response['baseUri']]
        );

        return new Response(
            statusCode: $response['statusCode'],
            items: [$issue],
        );
    }

    public function update(Issue $issue): Response
    {
        $response = $this->http->sendRequest('put', "issues/{$issue->id}.json", ['issue' => $issue->serialize()]);

        return new Response(
            statusCode: $response['statusCode'],
        );
    }
}
