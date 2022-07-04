<?php

namespace OuestCode\RedmineApi\Dto\Items;

use Spatie\DataTransferObject\DataTransferObject;

class Activity extends DataTransferObject
{
    public int $id;

    public string $name;
}