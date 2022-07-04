<?php

namespace OuestCode\RedmineApi;

use OuestCode\RedmineApi\Dto\Items\Issue;
use OuestCode\RedmineApi\Dto\Items\Project;
use OuestCode\RedmineApi\Dto\Items\TimeEntry;
use OuestCode\RedmineApi\Dto\Items\Version;

class Response
{
    public function __construct(
        public array $items,
        public ?int $offset,
        public ?int $limit,
        public ?int $total,
    ) {
    }

    public static function make(string $type, array $rawResponse): self
    {
        $class = match ($type) {
            'issues', 'issue' => Issue::class,
            'projects' => Project::class,
            'versions' => Version::class,
            'time_entries' => TimeEntry::class,
        };

        $rawItems = match ($type) {
            'issue' => [$rawResponse[$type]],
            default => $rawResponse[$type],
        };

        $items = array_map(
            fn(array $properties) => new $class($properties),
            $rawItems
        );

        return new self(
            $items,
            $rawResponse['offset'] ?? null,
            $rawResponse['limit'] ?? null,
            $rawResponse['total_count'] ?? count($items),
        );
    }
}
