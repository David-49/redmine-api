<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\DataTransferObject;

class Relation extends DataTransferObject
{
    public int $id;
    public int $issue_id;
    public int $issue_to_id;
    public string $relation_type;
    public ?string $delay;
}
