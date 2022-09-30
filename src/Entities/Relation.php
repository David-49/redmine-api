<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class Relation extends DataTransferObject
{
    public int $id;

    #[MapFrom('issue_id')]
    public int $issueId;

    #[MapFrom('issue_to_id')]
    public int $issueToId;

    #[MapFrom('relation_type')]
    public string $relationType;

    public ?string $delay;
}
