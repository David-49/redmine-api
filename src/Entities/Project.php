<?php

namespace OuestCode\RedmineApi\Entities;

use DateTime;
use OuestCode\RedmineApi\Casters\DateTimeCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class Project extends DataTransferObject
{
    public int $id;

    public ?string $name;

    public ?string $description;

    public ?int $status;

    #[MapFrom('is_public')]
    public ?bool $isPublic;

    #[MapFrom('inherit_members')]
    public ?bool $inheritMembers;

    #[MapFrom('created_on')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $createdOn;

    #[MapFrom('updated_on')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $updatedOn;
}
