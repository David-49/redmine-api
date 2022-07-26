<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\DataTransferObject;

class Activity extends DataTransferObject
{
    public int $id;

    public string $name;
}
