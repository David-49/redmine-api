<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\DataTransferObject;

class Priority extends DataTransferObject
{
    public int $id;

    public ?string $name;
}
