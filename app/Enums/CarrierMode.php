<?php

namespace App\Enums;

enum CarrierMode: string
{
    case Sea = 'sea';
    case Air = 'air';
    case Land = 'land';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
