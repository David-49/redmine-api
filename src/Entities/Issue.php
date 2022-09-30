<?php

namespace OuestCode\RedmineApi\Entities;

use DateTime;
use OuestCode\RedmineApi\Casters\DateTimeCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

class Issue extends DataTransferObject
{
    public int $id;

    public ?string $baseUri;

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

    #[CastWith(ArrayCaster::class, itemType: Relation::class)]
    public ?array $relations;

    public ?string $note;

    public function getCustomField(string $fieldName): ?CustomField
    {
        if (! $this->customFields) {
            return null;
        }

        $field = array_filter($this->customFields, fn (CustomField $field) => $field->name === $fieldName);

        return array_pop($field);
    }

    public function getUrl(): ?string
    {
        if (! $this->baseUri) {
            return null;
        }

        $baseUri = parse_url($this->baseUri);

        return sprintf('%s://%s/issues/%s', $baseUri['scheme'], $baseUri['host'], $this->id);
    }

    public function serialize(): array
    {
        return [
            'subject' => $this->subject,
            'description' => $this->description,
            'project_id' => $this->project?->id,
            'tracker_id' => $this->tracker?->id,
            'status_id' => $this->status?->id,
            'parent_issue_id' => $this->parent?->id,
            'priority_id' => $this->priority?->id,
            'fixed_version_id' => $this->version?->id,
            'estimated_hours' => $this->estimatedHours,
            'custom_fields' => array_map(fn (CustomField $field) => $field->serialize(), $this->customFields ?? []),
            'notes' => $this->note,
        ];
    }
}
