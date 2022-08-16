<?php

namespace Tests;

use DateTime;
use OuestCode\RedmineApi\Client;
use OuestCode\RedmineApi\Entities\Project;
use OuestCode\RedmineApi\Entities\Response;

class CanManageProjectTest extends TestCase
{
    /** @test */
    public function can_have_all()
    {
        $http = $this->createHttpMock(
            body: '{"projects":[{"id":1,"name":"Kanban POP","identifier":"kanban-pop","description":"","status":1,"is_public":false,"inherit_members":false,"created_on":"2021-09-24T13:44:26Z","updated_on":"2021-09-24T13:44:26Z"}],"total_count":1,"offset":0,"limit":25}',
        );

        $redmine = new Client($http);

        $response = $redmine->project()->all();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $response->items);
        $this->assertContainsOnly(Project::class, $response->items);

        $this->assertEquals(1, $response->total);
        $this->assertEquals(0, $response->offset);
        $this->assertEquals(25, $response->limit);

        $project = $response->items[0];

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals(1, $project->id);
        $this->assertEquals('Kanban POP', $project->name);
        $this->assertEquals(null, $project->description);
        $this->assertEquals(1, $project->status);
        $this->assertEquals(false, $project->isPublic);
        $this->assertEquals(false, $project->inheritMembers);

        $this->assertInstanceOf(DateTime::class, $project->createdOn);
        $this->assertEquals('2021-09-24', $project->createdOn->format('Y-m-d'));

        $this->assertInstanceOf(DateTime::class, $project->updatedOn);
        $this->assertEquals('2021-09-24', $project->updatedOn->format('Y-m-d'));
    }
}
