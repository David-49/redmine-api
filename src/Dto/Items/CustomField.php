<?php

namespace OuestCode\RedmineApi\Dto\Items;

use Spatie\DataTransferObject\DataTransferObject;

class CustomField extends DataTransferObject
{
    public int $id;

    public string $name;

    public mixed $value;
}
