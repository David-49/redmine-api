<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\DataTransferObject;

class Status extends DataTransferObject
{
    public int $id;

    public string $name;
}
