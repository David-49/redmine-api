<?php

namespace OuestCode\RedmineApi\Entities;

use DateTime;
use OuestCode\RedmineApi\Casters\DateTimeCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class Journal extends DataTransferObject
{
    public int $id;

    #[MapFrom('user')]
    public Author $author;

    public ?string $notes;

    #[MapFrom('created_on')]
    #[CastWith(DateTimeCaster::class)]
    public DateTime $createdOn;

    #[MapFrom('private_notes')]
    public bool $privateNotes;

    public array $details;
}
