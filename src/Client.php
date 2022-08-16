<?php

namespace OuestCode\RedmineApi;

use OuestCode\RedmineApi\Exceptions\UnexpectedProviderException;
use OuestCode\RedmineApi\Providers\IssueProvider;
use OuestCode\RedmineApi\Providers\ProjectProvider;
use OuestCode\RedmineApi\Providers\TimeEntryProvider;
use OuestCode\RedmineApi\Providers\VersionProvider;

/**
 * @method IssueProvider issue()
 * @method ProjectProvider project()
 * @method VersionProvider version()
 * @method TimeEntryProvider timeEntry()
 */
class Client
{
    public function __construct(
        protected HttpHandler $http,
    ) {
    }

    public function __call(string $name, array $arguments = [])
    {
        return match ($name) {
            'issue' => new IssueProvider($this->http),
            'project' => new ProjectProvider($this->http),
            'version' => new VersionProvider($this->http),
            'timeEntry' => new TimeEntryProvider($this->http),
            default => throw new UnexpectedProviderException($name),
        };
    }

    public function getHttpHandler(): HttpHandler
    {
        return $this->http;
    }
}
