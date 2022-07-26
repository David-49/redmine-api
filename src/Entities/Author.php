<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\DataTransferObject;

class Author extends DataTransferObject
{
    public int $id;

    public string $name;
}
