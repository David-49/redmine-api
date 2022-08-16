<?php

namespace OuestCode\RedmineApi\Entities;

use Spatie\DataTransferObject\DataTransferObject;

class Response extends DataTransferObject
{
    public int $statusCode;
    public ?array $items;
    public ?int $offset;
    public ?int $limit;
    public ?int $total;
}
