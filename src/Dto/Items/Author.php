<?php

namespace OuestCode\RedmineApi\Dto\Items;

use Spatie\DataTransferObject\DataTransferObject;

class Author extends DataTransferObject
{
    public int $id;

    public string $name;
}
