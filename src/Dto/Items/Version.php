<?php

namespace OuestCode\RedmineApi\Dto\Items;

use DateTime;
use OuestCode\RedmineApi\Casters\DateTimeCaster;
use OuestCode\RedmineApi\Dto\Items\Project;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class Version extends DataTransferObject
{
    public int $id;

    public string $name;

    public ?Project $project;

    public ?string $description;

    public ?string $status;

    #[MapFrom('due_date')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $dueDate;

    public ?string $sharing;

    #[MapFrom('wiki_page_title')]
    public ?string $wikiPageTitle;

    #[MapFrom('created_on')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $createdOn;

    #[MapFrom('updated_on')]
    #[CastWith(DateTimeCaster::class)]
    public ?DateTime $updatedOn;
}
