<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\DataTransferObject;

class Relation extends DataTransferObject
{
    public int $id;
    public int $issueId;
    public int $issueToId;
    public string $relationType;
    public ?string $delay;
}
