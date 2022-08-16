<?php

namespace Tests;

use OuestCode\RedmineApi\Client;
use OuestCode\RedmineApi\HttpHandler;
use OuestCode\RedmineApi\Providers\IssueProvider;
use OuestCode\RedmineApi\Providers\ProjectProvider;
use OuestCode\RedmineApi\Providers\TimeEntryProvider;
use OuestCode\RedmineApi\Providers\VersionProvider;

class ClientTest extends TestCase
{
    /** @test */
    public function can_call_providers()
    {
        $http = $this->createHttpMock();

        $client = new Client($http);

        $this->assertInstanceOf(IssueProvider::class, $client->issue());
        $this->assertInstanceOf(ProjectProvider::class, $client->project());
        $this->assertInstanceOf(VersionProvider::class, $client->version());
        $this->assertInstanceOf(TimeEntryProvider::class, $client->timeEntry());
    }

    /** @test */
    public function can_have_http_handler()
    {
        $http = $this->createHttpMock();

        $client = new Client($http);

        $this->assertInstanceOf(HttpHandler::class, $client->getHttpHandler());
    }
}
