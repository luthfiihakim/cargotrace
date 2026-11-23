<?php

namespace App\Enums;

enum LocationType: string
{
    case Port = 'port';
    case Airport = 'airport';
    case Warehouse = 'warehouse';
    case Hub = 'hub';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
