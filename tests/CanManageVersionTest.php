<?php

namespace Tests;

use DateTime;
use OuestCode\RedmineApi\Client;
use OuestCode\RedmineApi\Entities\Project;
use OuestCode\RedmineApi\Entities\Response;
use OuestCode\RedmineApi\Entities\Version;

class CanManageVersionTest extends TestCase
{
    /** @test */
    public function can_have_all()
    {
        $http = $this->createHttpMock(
            '{"versions":[{"id":1,"project":{"id":1,"name":"Kanban POP"},"name":"v0.1","description":"","status":"open","due_date":null,"sharing":"none","wiki_page_title":null,"created_on":"2021-10-05T20:24:17Z","updated_on":"2021-10-05T20:24:17Z"}],"total_count":1}',
        );

        $redmine = new Client($http);

        $project = new Project(id: 1);

        $response = $redmine->version()->all($project);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $response->items);
        $this->assertContainsOnly(Version::class, $response->items);

        $this->assertEquals(1, $response->total);

        $version = $response->items[0];

        $this->assertInstanceOf(Version::class, $version);
        $this->assertEquals(1, $version->id);
        $this->assertEquals('v0.1', $version->name);

        $this->assertInstanceOf(Project::class, $version->project);
        $this->assertEquals(1, $version->project->id);
        $this->assertEquals('Kanban POP', $version->project->name);

        $this->assertEquals(null, $version->description);
        $this->assertEquals('open', $version->status);
        $this->assertEquals(null, $version->dueDate);
        $this->assertEquals('none', $version->sharing);
        $this->assertEquals(null, $version->wikiPageTitle);

        $this->assertInstanceOf(DateTime::class, $version->createdOn);
        $this->assertEquals('2021-10-05', $version->createdOn->format('Y-m-d'));

        $this->assertInstanceOf(DateTime::class, $version->updatedOn);
        $this->assertEquals('2021-10-05', $version->updatedOn->format('Y-m-d'));
    }
}
