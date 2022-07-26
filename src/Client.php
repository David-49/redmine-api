<?php

namespace OuestCode\RedmineApi;

use OuestCode\RedmineApi\Entities\Issue;
use OuestCode\RedmineApi\Entities\Project;
use OuestCode\RedmineApi\Entities\Response;

class Client
{
    public function __construct(
        protected HttpHandler $http,
    ) {
    }

    public function getProjects(array $params = []): Response
    {
        return $this->http->sendRequest('get', 'projects.json', $params);
    }

    public function getIssues(array $params = []): Response
    {
        return $this->http->sendRequest('get', 'issues.json', $params);
    }

    public function getIssue(Issue $issue, array $params = []): Response
    {
        return $this->http->sendRequest(
            'get',
            sprintf('/issues/%s.json', $issue->id),
            $params
        );
    }

    public function getVersions(Project $project, array $params = []): Response
    {
        return $this->http->sendRequest(
            'get',
            sprintf('projects/%s/versions.json', $project->id),
            $params
        );
    }

    public function getTimeEntries(array $params = []): Response
    {
        return $this->http->sendRequest('get', 'time_entries.json', $params);
    }
}
