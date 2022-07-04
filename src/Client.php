<?php

namespace OuestCode\RedmineApi;

use GuzzleHttp\Client as Http;
use OuestCode\RedmineApi\Dto\Items\Issue;
use OuestCode\RedmineApi\Dto\Items\Project;

class Client
{
    private Http $http;

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    public function getProjects(array $params = []): Response
    {
        $response = $this->request('projects.json', $params);

        return Response::make('projects', $response);
    }

    public function getIssues(array $params = []): Response
    {
        $response = $this->request('issues.json', $params);

        return Response::make('issues', $response);
    }

    public function getIssue(Issue $issue, array $params = []): Response
    {
        $response = $this->request(
            sprintf('/issues/%s.json', $issue->id),
            $params
        );

        return Response::make('issue', $response);
    }

    public function getVersions(Project $project, array $params = []): Response
    {
        $response = $this->request(
            sprintf('projects/%s/versions.json', $project->id),
            $params
        );

        return Response::make('versions', $response);
    }

    public function getTimeEntries(array $params = []): Response
    {
        $response = $this->request('time_entries.json', $params);

        return Response::make('time_entries', $response);
    }

    protected function request(string $uri, array $params = []): array
    {
        $response = $this->http->get($uri, ['query' => $params]);

        return json_decode(
            json: $response->getBody()->getContents(),
            associative: null,
            flags: JSON_OBJECT_AS_ARRAY
        );
    }
}
