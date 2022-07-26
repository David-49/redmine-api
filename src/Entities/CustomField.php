<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\DataTransferObject;

class CustomField extends DataTransferObject
{
    public int $id;

    public string $name;

    public mixed $value;
}
