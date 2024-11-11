<?php

namespace App\Enum;

enum OrderStatus: string
{
    case InPreparation = 'in_preparation';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Canceled = 'canceled';
}
