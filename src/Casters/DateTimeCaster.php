<?php

namespace OuestCode\RedmineApi\Casters;

use DateTime;
use Spatie\DataTransferObject\Caster;

class DateTimeCaster implements Caster
{
    public function cast(mixed $value): DateTime
    {
        return new DateTime($value);
    }
}
