<?php

namespace Tests;

use DateTime;
use OuestCode\RedmineApi\Client;
use OuestCode\RedmineApi\Entities\Activity;
use OuestCode\RedmineApi\Entities\Author;
use OuestCode\RedmineApi\Entities\Issue;
use OuestCode\RedmineApi\Entities\Project;
use OuestCode\RedmineApi\Entities\Response;
use OuestCode\RedmineApi\Entities\TimeEntry;

class CanManageTimeEntryTest extends TestCase
{
    /** @test */
    public function can_have_all()
    {
        $http = $this->createHttpMock(
            '{"time_entries":[{"id":1,"project":{"id":1,"name":"Kanban POP"},"issue":{"id":1},"user":{"id":1,"name":"Redmine Admin"},"activity":{"id":4,"name":"Recherche"},"hours":7.25,"comments":"","spent_on":"2021-11-16","created_on":"2021-11-16T22:37:42Z","updated_on":"2021-11-16T22:37:42Z"}],"total_count":1,"offset":0,"limit":25}',
        );

        $redmine = new Client($http);

        $response = $redmine->timeEntry()->all();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $response->items);
        $this->assertContainsOnly(TimeEntry::class, $response->items);

        $this->assertEquals(1, $response->total);
        $this->assertEquals(0, $response->offset);
        $this->assertEquals(25, $response->limit);

        $timeEntry = $response->items[0];

        $this->assertInstanceOf(TimeEntry::class, $timeEntry);
        $this->assertEquals(1, $timeEntry->id);

        $this->assertInstanceOf(Project::class, $timeEntry->project);
        $this->assertEquals(1, $timeEntry->project->id);
        $this->assertEquals('Kanban POP', $timeEntry->project->name);

        $this->assertInstanceOf(Issue::class, $timeEntry->issue);
        $this->assertEquals(1, $timeEntry->issue->id);
        $this->assertNull($timeEntry->issue->getUrl());

        $this->assertInstanceOf(Author::class, $timeEntry->author);
        $this->assertEquals(1, $timeEntry->author->id);
        $this->assertEquals('Redmine Admin', $timeEntry->author->name);

        $this->assertInstanceOf(Activity::class, $timeEntry->activity);
        $this->assertEquals(4, $timeEntry->activity->id);
        $this->assertEquals('Recherche', $timeEntry->activity->name);

        $this->assertEquals(7.25, $timeEntry->hours);
        $this->assertEquals('', $timeEntry->comments);

        $this->assertInstanceOf(DateTime::class, $timeEntry->spentOn);
        $this->assertEquals('2021-11-16', $timeEntry->spentOn->format('Y-m-d'));

        $this->assertInstanceOf(DateTime::class, $timeEntry->createdOn);
        $this->assertEquals('2021-11-16', $timeEntry->createdOn->format('Y-m-d'));

        $this->assertInstanceOf(DateTime::class, $timeEntry->updatedOn);
        $this->assertEquals('2021-11-16', $timeEntry->updatedOn->format('Y-m-d'));
    }
}
