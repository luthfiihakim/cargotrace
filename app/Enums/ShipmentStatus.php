<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case Created = 'CREATED';
    case Booked = 'BOOKED';
    case PickedUp = 'PICKED_UP';
    case InTransit = 'IN_TRANSIT';
    case Delivered = 'DELIVERED';
    case Cancelled = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::Created => 'Created',
            self::Booked => 'Booked',
            self::PickedUp => 'Picked up',
            self::InTransit => 'In transit',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Delivered, self::Cancelled], true);
    }
}
