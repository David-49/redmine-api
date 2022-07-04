<?php

namespace OuestCode\RedmineApi\Dto\Items;

use DateTime;
use OuestCode\RedmineApi\Casters\DateTimeCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

class Issue extends DataTransferObject
{
    public int $id;

    public ?string $subject;

    public ?string $description;

    public ?Project $project;

    public ?Tracker $tracker;

    public ?Status $status;

    public ?Issue $parent;

    public ?Priority $priority;

    public ?Author $author;

    #[MapFrom('fixed_version')]
    public ?Version $version;

    #[MapFrom('start_date')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $startDate;

    #[MapFrom('due_date')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $dueDate;

    #[MapFrom('done_ratio')]
    public ?int $doneRatio;

    #[MapFrom('is_private')]
    public ?bool $isPrivate;

    #[MapFrom('estimated_hours')]
    public ?int $estimatedHours;

    #[MapFrom('custom_fields')]
    #[CastWith(ArrayCaster::class, itemType: CustomField::class)]
    public ?array $customFields;

    #[CastWith(ArrayCaster::class, itemType: Journal::class)]
    public ?array $journals;

    #[MapFrom('created_on')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $createdOn;

    #[MapFrom('updated_on')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $updatedOn;

    #[MapFrom('closed_on')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $closedOn;
}
