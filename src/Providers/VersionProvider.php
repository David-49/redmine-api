<?php

namespace OuestCode\RedmineApi\Providers;

use OuestCode\RedmineApi\Entities\Project;
use OuestCode\RedmineApi\Entities\Response;
use OuestCode\RedmineApi\Entities\Version;
use OuestCode\RedmineApi\HttpHandler;

class VersionProvider
{
    public function __construct(protected HttpHandler $http)
    {
    }

    public function all(Project $project, array $params = []): Response
    {
        $response = $this->http->sendRequest('get', "projects/{$project->id}/versions.json", $params);

        $versions = array_map(
            function (array $properties) use ($response) {
                return new Version($properties);
            },
            $response['body']['versions'],
        );

        return new Response(
            statusCode: $response['statusCode'],
            items: $versions,
            offset: $response['body']['offset'] ?? null,
            limit: $response['body']['limit'] ?? null,
            total: $response['body']['total_count'] ?? count($versions)
        );
    }
}
