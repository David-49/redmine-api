<?php

namespace Tests;

use DateTime;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use OuestCode\RedmineApi\Client;
use OuestCode\RedmineApi\Entities\Activity;
use OuestCode\RedmineApi\Entities\Author;
use OuestCode\RedmineApi\Entities\CustomField;
use OuestCode\RedmineApi\Entities\Issue;
use OuestCode\RedmineApi\Entities\Journal;
use OuestCode\RedmineApi\Entities\TimeEntry;
use OuestCode\RedmineApi\Entities\Priority;
use OuestCode\RedmineApi\Entities\Project;
use OuestCode\RedmineApi\Entities\Status;
use OuestCode\RedmineApi\Entities\Tracker;
use OuestCode\RedmineApi\Entities\Version;
use OuestCode\RedmineApi\Entities\Response;
use OuestCode\RedmineApi\HttpHandler;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as Http;

class ClientTest extends TestCase
{
    /** @test */
    public function can_get_projects()
    {
        $http = $this->createHttpMock(
            '{"projects":[{"id":1,"name":"Kanban POP","identifier":"kanban-pop","description":"","status":1,"is_public":false,"inherit_members":false,"created_on":"2021-09-24T13:44:26Z","updated_on":"2021-09-24T13:44:26Z"}],"total_count":1,"offset":0,"limit":25}',
        );

        $redmine = new Client($http);

        $response = $redmine->getProjects();

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

    /** @test */
    public function can_get_issues()
    {
        $http = $this->createHttpMock(
            '{"issues":[{"id":2,"project":{"id":1,"name":"Kanban POP"},"tracker":{"id":2,"name":"tech"},"status":{"id":1,"name":"New"},"priority":{"id":1,"name":"Low"},"author":{"id":1,"name":"Redmine Admin"},"fixed_version":{"id":1,"name":"v0.1"},"subject":"Mise à jour Laravel 9","description":"","start_date":"2021-10-05","due_date":null,"done_ratio":0,"is_private":false,"estimated_hours":null,"custom_fields":[{"id":1,"name":"Valeur métier","value":"1000"},{"id":2,"name":"Complexité","value":"3"}],"created_on":"2021-10-05T20:08:55Z","updated_on":"2021-10-05T20:24:52Z","closed_on":null},{"id":1,"project":{"id":1,"name":"Kanban POP"},"tracker":{"id":1,"name":"feat"},"status":{"id":2,"name":"In progress"},"priority":{"id":1,"name":"Low"},"author":{"id":1,"name":"Redmine Admin"},"subject":"Vers l\'infini et au delà","description":"C\'est mieux ici.","start_date":"2021-09-24","due_date":null,"done_ratio":0,"is_private":false,"estimated_hours":null,"custom_fields":[{"id":1,"name":"Valeur métier","value":null},{"id":2,"name":"Complexité","value":null}],"created_on":"2021-09-24T13:51:40Z","updated_on":"2021-10-05T19:44:54Z","closed_on":null}],"total_count":2,"offset":0,"limit":5}',
        );

        $redmine = new Client($http);

        $response = $redmine->getIssues();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(2, $response->items);
        $this->assertContainsOnly(Issue::class, $response->items);

        $this->assertEquals(2, $response->total);
        $this->assertEquals(0, $response->offset);
        $this->assertEquals(5, $response->limit);

        $issue = $response->items[0];

        $this->assertInstanceOf(Issue::class, $issue);
        $this->assertEquals(2, $issue->id);
        $this->assertEquals('Mise à jour Laravel 9', $issue->subject);
        $this->assertEquals('', $issue->description);

        $this->assertInstanceOf(Project::class, $issue->project);
        $this->assertEquals(1, $issue->project->id);
        $this->assertEquals('Kanban POP', $issue->project->name);

        $this->assertInstanceOf(Tracker::class, $issue->tracker);
        $this->assertEquals(2, $issue->tracker->id);
        $this->assertEquals('tech', $issue->tracker->name);

        $this->assertInstanceOf(Status::class, $issue->status);
        $this->assertEquals(1, $issue->status->id);
        $this->assertEquals('New', $issue->status->name);

        $this->assertInstanceOf(Priority::class, $issue->priority);
        $this->assertEquals(1, $issue->priority->id);
        $this->assertEquals('Low', $issue->priority->name);

        $this->assertInstanceOf(Author::class, $issue->author);
        $this->assertEquals(1, $issue->author->id);
        $this->assertEquals('Redmine Admin', $issue->author->name);

        $this->assertInstanceOf(Version::class, $issue->version);
        $this->assertEquals(1, $issue->version->id);
        $this->assertEquals('v0.1', $issue->version->name);

        $this->assertInstanceOf(DateTime::class, $issue->startDate);
        $this->assertEquals('2021-10-05', $issue->startDate->format('Y-m-d'));

        $this->assertEquals(null, $issue->dueDate);
        $this->assertEquals(0, $issue->doneRatio);
        $this->assertEquals(false, $issue->isPrivate);
        $this->assertEquals(null, $issue->estimatedHours);

        $this->assertIsArray($issue->customFields);
        $this->assertInstanceOf(CustomField::class, $issue->customFields[0]);
        $this->assertEquals(1, $issue->customFields[0]->id);
        $this->assertEquals('Valeur métier', $issue->customFields[0]->name);
        $this->assertEquals('1000', $issue->customFields[0]->value);

        $this->assertInstanceOf(DateTime::class, $issue->createdOn);
        $this->assertEquals('2021-10-05', $issue->createdOn->format('Y-m-d'));

        $this->assertInstanceOf(DateTime::class, $issue->updatedOn);
        $this->assertEquals('2021-10-05', $issue->updatedOn->format('Y-m-d'));

        $this->assertEquals(null, $issue->closedOn);
    }

    /** @test */
    public function can_get_versions()
    {
        $http = $this->createHttpMock(
            '{"versions":[{"id":1,"project":{"id":1,"name":"Kanban POP"},"name":"v0.1","description":"","status":"open","due_date":null,"sharing":"none","wiki_page_title":null,"created_on":"2021-10-05T20:24:17Z","updated_on":"2021-10-05T20:24:17Z"}],"total_count":1}',
        );

        $redmine = new Client($http);

        $project = new Project(['id' => 1, 'name' => 'Kanban POP']);

        $response = $redmine->getVersions($project);

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

    /** @test */
    public function can_get_time_entries()
    {
        $http = $this->createHttpMock(
            '{"time_entries":[{"id":1,"project":{"id":1,"name":"Kanban POP"},"issue":{"id":1},"user":{"id":1,"name":"Redmine Admin"},"activity":{"id":4,"name":"Recherche"},"hours":7.25,"comments":"","spent_on":"2021-11-16","created_on":"2021-11-16T22:37:42Z","updated_on":"2021-11-16T22:37:42Z"}],"total_count":1,"offset":0,"limit":25}',
        );

        $redmine = new Client($http);

        $response = $redmine->getTimeEntries();

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

    /** @test */
    public function can_get_issue()
    {
        $http = $this->createHttpMock(
            '{"issue":{"id":1,"project":{"id":1,"name":"Kanban POP"},"tracker":{"id":1,"name":"feat"},"status":{"id":2,"name":"In progress"},"priority":{"id":1,"name":"Low"},"author":{"id":1,"name":"Redmine Admin"},"subject":"Vers l\'infini et au delà","description":"","start_date":"2021-09-24","due_date":null,"done_ratio":0,"is_private":false,"estimated_hours":null,"total_estimated_hours":null,"spent_hours":7.25,"total_spent_hours":7.25,"created_on":"2021-09-24T13:51:40Z","updated_on":"2021-11-17T22:08:22Z","closed_on":null,"journals":[{"id":1,"user":{"id":1,"name":"Redmine Admin"},"notes":"","created_on":"2021-09-24T14:50:09Z","private_notes":false,"details":[{"property":"attr","name":"status_id","old_value":"1","new_value":"2"}]},{"id":2,"user":{"id":1,"name":"Redmine Admin"},"notes":"Test com","created_on":"2021-11-17T22:08:22Z","private_notes":false,"details":[]}]}}',
        );

        $redmine = new Client($http);

        $issue = new Issue(['id' => 1]);

        $response = $redmine->getIssue($issue);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $response->items);
        $this->assertContainsOnly(Issue::class, $response->items);

        $issue = $response->items[0];

        $this->assertInstanceOf(Issue::class, $issue);
        $this->assertEquals(1, $issue->id);
        $this->assertEquals("Vers l'infini et au delà", $issue->subject);
        $this->assertEquals('', $issue->description);

        $this->assertInstanceOf(Project::class, $issue->project);
        $this->assertEquals(1, $issue->project->id);
        $this->assertEquals('Kanban POP', $issue->project->name);

        $this->assertInstanceOf(Tracker::class, $issue->tracker);
        $this->assertEquals(1, $issue->tracker->id);
        $this->assertEquals('feat', $issue->tracker->name);

        $this->assertInstanceOf(Status::class, $issue->status);
        $this->assertEquals(2, $issue->status->id);
        $this->assertEquals('In progress', $issue->status->name);

        $this->assertInstanceOf(Priority::class, $issue->priority);
        $this->assertEquals(1, $issue->priority->id);
        $this->assertEquals('Low', $issue->priority->name);

        $this->assertInstanceOf(Author::class, $issue->author);
        $this->assertEquals(1, $issue->author->id);
        $this->assertEquals('Redmine Admin', $issue->author->name);

        $this->assertNull($issue->version);

        $this->assertInstanceOf(DateTime::class, $issue->startDate);
        $this->assertEquals('2021-09-24', $issue->startDate->format('Y-m-d'));

        $this->assertEquals(null, $issue->dueDate);
        $this->assertEquals(0, $issue->doneRatio);
        $this->assertEquals(false, $issue->isPrivate);
        $this->assertEquals(null, $issue->estimatedHours);
        $this->assertNull($issue->customFields);

        $this->assertInstanceOf(DateTime::class, $issue->createdOn);
        $this->assertEquals('2021-09-24', $issue->createdOn->format('Y-m-d'));

        $this->assertInstanceOf(DateTime::class, $issue->updatedOn);
        $this->assertEquals('2021-11-17', $issue->updatedOn->format('Y-m-d'));

        $this->assertEquals(null, $issue->closedOn);

        $this->assertIsArray($issue->journals);
        $this->assertInstanceOf(Journal::class, $issue->journals[0]);
        $this->assertEquals(1, $issue->journals[0]->id);
        $this->assertEquals('', $issue->journals[0]->notes);
        $this->assertInstanceOf(DateTime::class,  $issue->journals[0]->createdOn);
        $this->assertEquals('2021-09-24', $issue->journals[0]->createdOn->format('Y-m-d'));
        $this->assertFalse($issue->journals[0]->privateNotes);
        $this->assertIsArray($issue->journals[0]->details);
    }

    protected function createHttpMock(string $body): HttpHandler
    {
        $mockHandler = new MockHandler([
            new GuzzleResponse(200, [], $body),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $http = new Http(['handler' => $handlerStack]);

        $httpHandler = new HttpHandler('https://redmine.dev', 'j.doe');
        $httpHandler->setHttpClient($http);

        return $httpHandler;
    }
}
