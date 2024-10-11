<?php

namespace App\Enum;

enum OrderStatus: string
{
    case InPreparation = 'IN_PREPARATION';
    case Shipped = 'SHIPPED';
    case Delivered = 'DELIVERED';
    case Canceled = 'CANCELED';
}
