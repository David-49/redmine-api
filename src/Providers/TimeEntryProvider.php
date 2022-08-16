<?php

namespace OuestCode\RedmineApi\Providers;

use OuestCode\RedmineApi\Entities\Response;
use OuestCode\RedmineApi\Entities\TimeEntry;
use OuestCode\RedmineApi\HttpHandler;

class TimeEntryProvider
{
    public function __construct(protected HttpHandler $http)
    {
    }

    public function all(array $params = []): Response
    {
        $response = $this->http->sendRequest('get', 'time_entries.json', $params);

        $timeEntries = array_map(
            function (array $properties) use ($response) {
                return new TimeEntry($properties);
            },
            $response['body']['time_entries'],
        );

        return new Response(
            statusCode: $response['statusCode'],
            items: $timeEntries,
            offset: $response['body']['offset'] ?? null,
            limit: $response['body']['limit'] ?? null,
            total: $response['body']['total_count'] ?? count($timeEntries)
        );
    }
}
